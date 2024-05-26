<?php

namespace App\Services;

use App\Enums\AcademyStatusEnum;
use App\Enums\StudentStatusEnum;
use App\Enums\TransactionTypeEnum;
use App\Models\Academy;
use App\Models\Student;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class StudentService
{
    private static Academy|null $academy;

    private array $serviceConfig;

    /**
     */
    public function __construct(Academy $academy = null)
    {
        self::$academy = $academy;

        $this->serviceConfig = config('dailykor.service');
    }

    /**
     * 서비스 시작시간 체크
     * @throws Exception
     */
    private function unableServiceCheck(): void
    {
        $studentStatusCheckDay = now()->format('d');
        $studentStatusCheckHour = now()->format('H');
        $unableServiceStart = $this->serviceConfig['unable_service_start'];
        if ($studentStatusCheckDay == $unableServiceStart['day'] && (
            $studentStatusCheckHour >= $unableServiceStart['hour_start']
            && $studentStatusCheckHour <= ($unableServiceStart['hour_end'] - 1)
        )
        ) {
            throw new Exception(__('messages.unable_to_start_service_due_to_calibration', [
                'hour_start' => $unableServiceStart['hour_start'],
                'hour_end' => $unableServiceStart['hour_end'],
            ]), 501);
        }
    }

    /**
     * 학원 매일국어 서비스 타입 체크
     * @throws Exception
     */
    private function academyServiceStatusCheck(): void
    {
        //        $bit = self::$academy->service_status & AcademyServiceStatusEnum::DK->value;
        //        if ($bit != AcademyServiceStatusEnum::DK->value) {
        //            throw new Exception(__('messages.student_service.it_s_not_an_academy_that_uses_dk'));
        //        }
    }

    /**
     * 학생서비스 시작
     * @throws Exception
     */
    public function serviceStart($students, array $args = []): array
    {
        try {
            $this->unableServiceCheck(); // 업데이트 금지 시간체크

            $successStudents = [];
            $failStudents = [];
            if (!is_null(self::$academy)) {
                $this->academyServiceStatusCheck(); // 학원타입 체크

                foreach ($students as $student) {
                    // 실패
                    $studentCheckResult = $this->studentStatusCheck($student);
                    if (!$studentCheckResult['result']) {
                        $failStudents[] = [
                            'student' => $student,
                            'message' => $studentCheckResult['message'],
                        ];
                        continue;
                    }
                    $successStudents[] = $student;
                }
            } else {
                $successStudents = $students;
            }

            DB::transaction(function () use ($successStudents, $args) {
                $serviceDate = $this->getServiceDate($args); // 기간산정

                $totalServicePrice = 0;
                $freeStudentCount = 0;

                // 정상학원인경우
                if (!is_null(self::$academy) && self::$academy->status == AcademyStatusEnum::PREMIUM) {
                    // 무료 사용갯수가 남아있다면 차감
                    $studentCount = count($successStudents);
                    if (self::$academy->free_student_cnt) {
                        if (self::$academy->free_student_cnt >= $studentCount) {
                            $freeStudentCount = $studentCount;
                            self::$academy->free_student_cnt -= $studentCount;
                        } else {
                            $freeStudentCount = self::$academy->free_student_cnt;
                            self::$academy->free_student_cnt = 0;
                        }

                        self::$academy->save();
                    }

                    $totalServicePrice = self::$academy->basic_price * ($studentCount - $freeStudentCount);
                }

                // 과금
                if ($totalServicePrice) {
                    $transactionResult = (new TransactionService())->set(self::$academy, [
                        'type' => TransactionTypeEnum::USE_SERVICE,
                        'amount' => $totalServicePrice,
                        'extra' => [
                            'students' => array_column($successStudents, 'name', 'id'),
                            'free_student_cnt' => $freeStudentCount,
                        ],
                    ]);
                    $approved = $transactionResult['result'];
                } else {
                    $approved = true;
                }

                if ($approved) {
                    foreach ($successStudents as $student) {
                        // 무료 체험일 경우 무료 체험 기록 제거
                        if ($student->status == StudentStatusEnum::FREE) {
                            $student->extra = null;
                            $student->training_results()->delete();
                        }

                        $student->status = StudentStatusEnum::IN_USE; // 사용중으로
                        $student->service_start_date = $serviceDate['start'];
                        $student->service_end_date = $serviceDate['end'];

                        $student->save(); // 학생정보 업데이트
                    }
                }
            });

            return [
                'result' => true,
                'success' => $successStudents,
                'fail' => $failStudents,
            ];
        } catch (\Throwable $e) {
            return [
                'result' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * 학생서비스 종료
     * 7일이내 결제금액이 있다면 환불
     * 종료시 자동연장 종료됨
     * @throws Exception
     */
    public function serviceStop(array $students): array
    {
        try {
            $failStudents = [];
            $successStudents = [];
            foreach ($students as $student) {
                // 사용중 학생이 아닌경우 제외
                $studentCheckResult = $this->studentStatusCheck($student, 'stop');
                if (!$studentCheckResult['result']) {
                    $failStudents[] = [
                        'student' => $student,
                        'message' => $studentCheckResult['message'],
                    ];
                    continue;
                }

                $successStudents[] = $student;
            }


            DB::transaction(function () use ($successStudents) {
                // 최근 서비스 시작을 7일내에 했다면 결제금액 환불
                $studentServices = \App\Models\StudentService::with('transaction')->whereIn('student_id', array_column($successStudents, 'id'))->where('created_at', '>=', now()->today()->subDays(7))->latest('id')->get()->groupBy('student_id');


                foreach ($successStudents as $student) {
                    // 서비스 시작이라면 서비스 종료 가능
                    if (
                        isset($studentServices[$student->id])
                        && $studentServices[$student->id][0]->type == 1
                        && $studentServices[$student->id][0]?->transaction?->amount
                    ) {
                        // 학원 포인트 환불
                        $transaction = (new TransactionService())->set($student->academy, [
                            'type' => TransactionTypeEnum::CHARGE_REFUND_SERVICE,
                            'amount' => $studentServices[$student->id][0]->transaction->amount,
                            'extra' => [],
                        ]);
                    }

                    // 정지등록
                    $student->services()->create([
                        'type' => 0,
                        'transaction_id' => $transaction['transaction_id'] ?? null,
                    ]);

                    // 학생정보수정
                    $student->status = StudentStatusEnum::STOP;
                    $student->service_continue = 0;
                    $student->setActivitylogOptions([
                        'academy_id' => $student->academy_id,
                        'description' => '서비스정지',
                        'is_show' => 1,
                    ])->save();
                }
            });

            return [
                'result' => true,
                'success' => $successStudents,
                'fail' => $failStudents,
            ];
        } catch (\Throwable $e) {
            return [
                'result' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * 학생 서비스 단일시작
     *
     * @throws Exception
     */
    public function serviceStartOne(Student $student, array $args = [])
    {
        $lock = cache()->lock('student_service_start_one_student_id_'.$student->id, 10);
        try {
            $lock->block(5);
            $studentCheckResult = $this->studentStatusCheck($student);
            if (!$studentCheckResult['result']) {
                throw new Exception($studentCheckResult['message']);
            }

            return $this->serviceStart([$student], $args);
        } catch (LockTimeoutException $e) {
            throw new Exception('응답 대기시간 초과');
        } finally {
            optional($lock)->release();
        }
    }


    /**
     * 학생 서비스 시작 상태체크
     *
     * @param Student $student
     * @param string $type
     * @return array
     */
    private function studentStatusCheck(Student $student, string $type = 'start'): array
    {
        try {
            if ($type == 'start') {
                // 이미 매일국어 서비스 시작한 학생인지 검사
                if (!is_null(self::$academy) && $student->status == StudentStatusEnum::IN_USE && $student->service_end_date > now()->format('Y-m-d')) {
                    throw new Exception(__('messages.already_in_service'));
                }
            } elseif ($type == 'stop') {
                // 이용중이 아니라면
                if (!is_null(self::$academy) && $student->status != StudentStatusEnum::IN_USE) {
                    throw new Exception(__('messages.invalid_request'));
                }
            }

            //            if ($student->model_reservations()->where([
            //                'type' => ModelReservationTypeEnum::STUDENT_STATUS,
            //            ])->wait()->exists()) {
            //                throw new Exception(__('messages.have_a_reservation_status'));
            //            }

            // 학원에 소속된 학생인지 검사
            if (!is_null(self::$academy) && $student->academy_id != self::$academy->id) {
                throw new Exception(__('messages.invalid_request'));
            }

            return [
                'result' => true,
            ];
        } catch (Exception $e) {
            return [
                'result' => false,
                'message' => $e->getMessage(),
            ];
        }
    }


    /**
     * 학생 서비스 정지
     *
     * @throws Exception
     */
    public function serviceStopOne(Student $student): array
    {
        if (is_null(self::$academy->id)) {
            self::$academy = $student->academy;
        }

        $lock = cache()->lock('student_service_stop_one_student_id_'.$student->id, 10);
        try {
            $lock->block(5);
            $studentCheckResult = $this->studentStatusCheck($student, 'stop');
            if (!$studentCheckResult['result']) {
                throw new Exception($studentCheckResult['message']);
            }

            return $this->serviceStop([$student]);
        } catch (LockTimeoutException $e) {
            throw new Exception('응답 대기시간 초과', Response::HTTP_INTERNAL_SERVER_ERROR);
        } finally {
            optional($lock)->release();
        }
    }

    /**
     * 기간산정
     *
     * @throws ValidationException
     */
    private function getServiceDate(array $args = []): array
    {
        Validator::make($args, [
            'service_start_date' => 'date',
            'service_end_date' => 'date',
        ])->validate();


        $serviceStartDate = ($args['service_start_date'] ?? '');
        $serviceEndDate = ($args['service_end_date'] ?? '');

        if ($serviceStartDate == '' && $serviceEndDate == '') {
            // b2c 외에는 서비스 시작일/종료일이 지정 되어 있지 않기 때문에 시작일/종료일 계산
            if (now()->day > 20) {
                // 오늘 날짜가 20일이 지났다면 다음 달 1일 부터 시작
                $now = now()->startOfMonth()->addMonth();
            } else {
                // 오늘 날짜가 20일 이전 오늘 날짜 부터 시작
                $now = now();
            }

            $serviceStartDate = date('Y-m-d');
            $serviceEndDate = $now->format('Y-m-t');
        }

        return [
            'start' => $serviceStartDate,
            'end' => $serviceEndDate,
        ];
    }

    /*
     * 학원변경
     */
    public function academyChange(Student $student, int $new_academy_id): array
    {
        try {
            if (!Academy::find($new_academy_id)) {
                throw new Exception('존재하지 않는 학원입니다.', Response::HTTP_NOT_FOUND);
            }

            $student->academy_id = $new_academy_id;
            $student->setActivitylogOptions([
                'academy_id' => $new_academy_id,
                'description' => '학원정보 변경',
            ])->save();

            return [
                'result' => true,
                'data' => $student,
            ];
        } catch (Exception $e) {
            return [
                'result' => false,
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
            ];
        }
    }

    /**
     * 추천 코드 유효성 체크
     * @param $code
     * @return array
     * @throws Exception
     */
    public function referralCheck($code): array
    {
        $referrals = config('dailykor.service.referrals') ?? [];

        if (!isset($referrals[$code])) {
            throw new Exception(__('messages.app.students.does_not_exist'), 403);
        }

        $referralInfo = $referrals[$code] ?: [];
        if (!now()->between($referralInfo['register_start'], $referralInfo['register_end'])) {
            throw new Exception(__('messages.app.students.referral_check.non_membership_period'), 403);
        }

        return $referralInfo + ['code' => $code];
    }

    /**
     * 무료체험 종료 체크 후 종료시 extra 값 변경
     */
    public function checkExpiredFree(Student $student)
    {
        $extra = $student->extra;

        if (!isset($extra['free_trial']) || $extra['free_trial']['expired']) {
            return;
        }

        $targetDate = Carbon::createFromFormat('Y-m-d', $student->extra['free_trial']['end_date']);
        $today = Carbon::today();

        // 기간 지났는지 체크
        $isPast = $today->gt($targetDate);

        // 소단원 1회차와 진단평가를 완료 했는지
        $completedCurriculum = $student->training_results()->whereNotNull('completed_at')->count() >= 3;
        $completedTest =  $student->test_results()->whereNotNull('completed_at')->count() >= 1;

        // 무
        if ($isPast || ($completedCurriculum && $completedTest)) {
            $extra['free_trial']['expired'] = true;
            $student->update(['extra' => $extra]);
        }
    }
}
