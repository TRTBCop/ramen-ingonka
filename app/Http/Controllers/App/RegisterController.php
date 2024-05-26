<?php

namespace App\Http\Controllers\App;

use App\Enums\StudentStatusEnum;
use App\Http\Controllers\BaseController;
use App\Http\Requests\App\StudentRequest;
use App\Services\StudentService;
use App\Models\Student;
use App\Models\StudentPhone;
use App\Notifications\RegisterCompleted;
use App\Notifications\PhoneVerification;
use Exception;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Crypt;
use Inertia\Inertia;
use Inertia\Response;

class RegisterController extends BaseController
{
    private StudentService $studentService;

    public function __construct(
        public string $name = '회원가입',
    ) {
        $this->studentService = new StudentService();
    }

    /**
     * 회원가입 폼
     * GET|HEAD | api/register | app.register.create
     *
     * @return RedirectResponse|Response
     */
    public function create(): Response|RedirectResponse
    {
        $referralInfo = [];
        if (request()->referral_code) {
            try {
                $referralInfo = $this->studentService->referralCheck(request()->referral_code);
            } catch (Exception $e) {
                return to_route('brand.index')->with('message', ['error', $e->getMessage()]);
            }
        }

        return Inertia::render('register/Create', [
            'kakao_id' => session('kakao_id'),
            'naver_id' => session('naver_id'),
            'agree' => setting('agree'),
            'privacy' => setting('privacy'),
            'marketing' => setting('marketing'),
            'referral_info' => $referralInfo,
        ]);
    }

    /**
     * 회원가입
     * POST | app/register | app.register.store
     *
     * @param StudentRequest $request
     * @return RedirectResponse|Response
     */
    public function store(StudentRequest $request): Response|RedirectResponse
    {
        $input = $request->validate($request->store());

        if (isset($input['password'])) {
            $input['password'] = bcrypt($input['password']);
        }
        $input['extra'] = [];

        // 휴대폰 인증 결과 확인
        $studentPhone = StudentPhone::where('created_at', '>', now()->subHour())
            ->where([
                'phone' => $request->parents_phone, // 학부모 전화번호와 인증번호휴대폰 체크
                'student_id' => null // 학생매칭 안된 경우
            ])->verified()->find($request->student_phone_id);

        // 인증번호확인이 안되었다면
        if (!$studentPhone) {
            return to_route('app.register.create')->with('message', ['error', __('messages.app.invalid_request', ['comment' => ''])]);
        }

        // 학생명-학부모는 1개의 데이터만 존재해야함
        if (Student::where([
            'name' => $request->name,
            'parents_phone' => $request->parents_phone
        ])->exists()) {
            return to_route('app.register.create')->with('message', ['error', __('messages.app.students.duplicate_data_found')]);
        }

        // 무료체험기간
        $period = config('dailykor.service.b2c.free_trial.period');

        // 리퍼럴 코드 처리
        $referralCode = $input['referral_code'] ?? '';

        // 추천 코드가 존재할 경우
        if ($referralCode) {
            try {
                $referralInfo = $this->studentService->referralCheck($referralCode);
                unset($input['referral']);
                $input['extra']['referrals'][] = $referralCode;    // 추천 코드 배열 형태로 추가

                $period = $referralInfo['period'];
            } catch (Exception $e) {
                // 잘못된 리퍼럴 코드 가입시 일반가입
            }
        }

        // 무료체험 기간 처리
        $freeTrialStartDate = now()->format('Y-m-d');
        $freeTrialEndDate = now()->addDays($period)->format('Y-m-d');
        $input['extra']['free_trial'] = [
            'start_date' => $freeTrialStartDate,
            'end_date' => $freeTrialEndDate,
            'expired' => false
        ];
        $input['status'] = StudentStatusEnum::FREE;
        $student = Student::create($input);    // 학생 등록

        // 인증회원 매칭
        $studentPhone->student_id = $student->id;
        $studentPhone->save();


        // 알림톡 발송 : 가입 완료
        $student->notify(new RegisterCompleted([
            $freeTrialStartDate, $freeTrialEndDate
        ]));

        $cryptStudentId = Crypt::encrypt($student->id);
        return to_route('app.register.result', ['id' => $cryptStudentId])->with('message', ['success', __('messages.app.common.success', ['name' => '회원가입'])]);
    }

    /**
     * 가입결과
     *
     * GET|HEAD | app/register/result | app.register.result
     * @return Response|RedirectResponse
     */
    public function result(): Response|RedirectResponse
    {
        try {
            $studentId = Crypt::decrypt(request('id'));
        } catch (DecryptException $e) {
            $studentId = null;
        }

        $student = $studentId ? Student::find($studentId) : null;

        if (!$student) {
            return to_route('brand.index')->with('message', ['error', __('messages.error_403')]);
        }

        return Inertia::render('register/Result', [
            'name' => $student->name,
            'access_id' => $student->access_id,
            'free_trial_period' => [
                'start' => $student['extra']['free_trial']['start_date'] ?? '',
                'end' => $student['extra']['free_trial']['end_date'] ?? '',
            ]
        ]);
    }

    /**
     * 아이디 중복 체크
     * POST | app/register | app.register.check-account
     *
     * @param StudentRequest $request
     * @return JsonResponse
     */
    public function checkAccount(StudentRequest $request): JsonResponse
    {
        $request->validate($request->verificationAccountCheck());

        return $this->sendResponse(
            [],
            __('messages.app.students.access_id_available')
        );
    }

    /**
     * 휴대폰 인증코드 발송
     * POST | app/register/verification-code-send | app.register.verification-code-send
     *
     * @param StudentRequest $request
     * @return JsonResponse
     */
    public function verificationCodeSend(StudentRequest $request): JsonResponse
    {
        $request->validate($request->verificationSendValidate());
        try {
            $verificationCode = rand(1000, 9999);
            $studentPhone = StudentPhone::create([
                'phone' => $request->phone,
                'code' => $verificationCode,
            ]);

            // 인증번호 발송
            $studentPhone->notify(new PhoneVerification([
                $verificationCode,
            ]));

            return $this->sendResponse([], __('messages.app.common.success', ['name' => '인증번호발송']));
        } catch (\Exception $e) {
            return $this->sendError(__('messages.app.common.failed', ['name' => '인증번호발송']), $e->getMessage(), $e->getCode());
        }
    }

    /**
     * 휴대폰 인증코드 확인
     * POST | app/register/verification-code-check | app.register.verification-code-check
     *
     * @param StudentRequest $request
     * @return JsonResponse
     */
    public function verificationCodeCheck(StudentRequest $request): JsonResponse
    {
        // 인증번호 확인
        $request->validate($request->verificationCodeValidate());

        try {
            $studentPhone = StudentPhone::where('created_at', '>', now()->subHour())
                ->where([
                    'phone' => $request->phone
                ])->unverified()->latest()->first();

            // 잘못된접근
            if (!$studentPhone) {
                throw new \Exception(__('messages.error_403'), 403);
            }

            // 유효하지 않은 코드
            if ($studentPhone->code != $request->code) {
                throw new \Exception(__('messages.app.common.check_verification_code'), 403);
            }

            // 인증완료
            $studentPhone->update([
                'verified' => 1,
            ]);

            return $this->sendResponse([
                'student_phone_id' => $studentPhone->id
            ], __('messages.app.common.success', ['name' => '인증']));
        } catch (\Exception $e) {
            return $this->sendError(__('messages.app.common.failed', ['name' => '인증']), $e->getMessage(), $e->getCode());
        }
    }

    /**
     * 아이디 찾기
     * GET | app/register/find-account | app.register.find-account
     *
     * @return Response
     */
    public function showFindAccount(): Response
    {
        return Inertia::render('register/FindAccount', []);
    }


    /**
     * 아이디 찾기 1단계
     * POST | app/register/find-account | app.register.find-account.store
     *
     * @param StudentRequest $request
     * @return Response
     */
    public function findAccount(StudentRequest $request): Response
    {
        // 아이디 찾기 (학생 이름 && 학부모 휴대폰)
        $request->validate($request->findAccessIdValidate());

        $student = Student::select(['id', 'access_id', 'parents_phone', 'created_at'])
            ->where([
                'name' => $request->name,
                'parents_phone' => $request->parents_phone
            ])->first();

        if ($student) {
            $response = [
                'student_id' => $student->id,
                'parents_phone' => $student->parents_phone,
                'access_id' => $student->access_id,
                'created_date' => $student->created_at->format('Y-m-d')
            ];
        } else {
            $response = [
                'student_id' => '',
                'access_id' => '',
                'created_date' => ''
            ];
        }

        return Inertia::render('register/FindAccountResult', $response);
    }

    /**
     * 완벽 아이디 찾기
     * POST | app/register/find-account-full | app.register.find-account.full
     *
     * @param StudentRequest $request
     * @return RedirectResponse|Response
     */
    public function findAccountFull(StudentRequest $request): Response|RedirectResponse
    {
        $request->validate($request->findAccessIdFullValidate());

        // 휴대폰 인증 결과 확인
        $studentPhone = StudentPhone::where('created_at', '>', now()->subHour())
            ->where([
                'phone' => $request->parents_phone, // 학부모 전화번호와 인증번호휴대폰 체크
            ])->verified()->find($request->student_phone_id);
        ;

        // 인증번호 실패시
        if (!$studentPhone) {
            return redirect()->back()->with('message', ['error', __('messages.app.phone_verification.failed')]);
        }

        $student = Student::select(['access_id', 'created_at'])
            ->where([
                'parents_phone' => $request->parents_phone
            ])->find($request->student_id);

        if (!$student) {
            return redirect()->back()->with('message', ['error', __('messages.invalid_request', ['comment' => ''])]);
        }

        return Inertia::render('register/FindAccountFullResult', [
            'access_id' => $student->access_id,
            'created_date' => $student->created_at->format('Y-m-d')
        ]);
    }

    /**
     * 비밀번호 찾기
     * GET | app/register/find-password | app.register.find-password
     *
     * @param StudentRequest $request
     * @return Response
     */
    public function showFindPassword(StudentRequest $request): Response
    {
        return Inertia::render('register/FindPassword');
    }

    /**
     * 비밀번호 찾기 처리
     * GET | app/register/find-password | p.register.find-password.store
     *
     * @param StudentRequest $request
     * @return Response
     */
    public function findPassword(StudentRequest $request): Response
    {
        // 비밀번호 찾기 (아이디 && 학생 이름 && 학부모 휴대폰)
        $request->validate($request->findPasswordValidate());

        $student = Student::where([
            'access_id' => $request->access_id,
        ])->first();

        $data = [
            'student_id' => '',
            'access_id' => '',
            'created_date' => '',
            'parents_phone' => ''
        ];
        $success = true;

        if ($student) {
            if ($student->name != $request->name) {
                $success = false;
            }
            if ($student->parents_phone != $request->parents_phone) {
                $success = false;
            }
        } else {
            $success = false;
        }

        if ($success) {
            $data = [
                'student_id' => $student->id,
                'access_id' => $student->access_id,
                'created_date' => $student->created_at->format('Y-m-d'),
                'parents_phone' => $student->parents_phone
            ];
        }

        return Inertia::render('register/FindPasswordReset', $data);
    }

    /**
     * 비밀번호 변경
     * POST app/register/find-password-reset app.register.find-password-reset
     *
     * @param StudentRequest $request
     * @return RedirectResponse|Response
     */
    public function findPasswordReset(StudentRequest $request): Response|RedirectResponse
    {
        $request->validate($request->resetPasswordValidate(), $request->messages());

        $student = Student::find($request->student_id);

        // 학생이 없는 경우
        if (!$student) {
            return redirect()->back()->with('message', ['error', __('messages.app.students.account_does_not_exist')]);
        }

        // 휴대폰 인증 결과 확인
        $studentPhone = StudentPhone::where('created_at', '>', now()->subHour())
            ->where([
                'phone' => $student->parents_phone, // 학부모 전화번호와 인증번호휴대폰 체크
                'student_id' => null // 학생매칭 안된 경우
            ])->verified()->find($request->student_phone_id);

        // 유효하지 않은 코드
        if (!$studentPhone) {
            return redirect()->back()->with('message', ['error', __('messages.app.invalid_request', ['comment' => ''])]);
        }

        // 인증회원 매칭
        $studentPhone->student_id = $student->id;
        $studentPhone->save();
        $student->setActivitylogOptions([
            'academy_id' => $student->academy_id,
            'description' => '비밀번호 재설정',
            'is_show' => 1,
        ])->update([
            'password' => bcrypt($request->password),
        ]);

        return Inertia::render('register/FindPasswordResult', [
            'access_id' => $student->access_id,
            'created_date' => $student->created_at->format('Y-m-d'),
        ]);
    }
}
