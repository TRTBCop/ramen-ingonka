<?php

namespace App\Http\Controllers\App;

use App\Enums\StudentStatusEnum;
use App\Http\Controllers\BaseController;
use App\Http\Requests\App\StudentGrandAndTermRequest;
use App\Http\Requests\App\StudentRequest;
use App\Models\Payment;
use App\Models\Student;
use App\Models\StudentPhone;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Exception;

class MyController extends BaseController
{
    /**
     * 마이페이지.내정보
     * GET|HEAD | app/my/profile | app.my.profile.show
     *
     * @return Response
     */
    public function showProfile(): Response
    {
        return Inertia::render('my/profile/Show', [
            'marketing' => setting('marketing'),
            'config' => [
                'training' => [
                    'grade_group' => config('dailykor.training.grade_group')
                ],
                'dbcode' => [
                    'students' => dbcode('students'),
                ],
            ],
        ]);
    }

    /**
     * 마이페이지.내정보 처리
     * PUT|PATCH | app/my/profile | app.my.profile.update
     *
     * @param StudentRequest $request
     * @return RedirectResponse
     */
    public function updateProfile(StudentRequest $request): RedirectResponse
    {
        $user = auth()->user();
        $input = $request->validate($request->update());

        if ($request->password) {
            $input['password'] = bcrypt($input['password']);
        }

        if ($request->student_phone_id) {
            // 휴대폰 인증 결과 확인
            $studentPhone = StudentPhone::where('created_at', '>', now()->subHour())
                ->where([
                    'phone' => $request->parents_phone, // 변경학부모 전화번호와 인증번호휴대폰 체크
                    'student_id' => null // 학생매칭 안된 경우
                ])->verified()->find($request->student_phone_id);

            $studentPhone->student_id = $user->id;
            $studentPhone->save();
        } else {
            unset($input['parents_phone']);
        }

        $user->setActivitylogOptions([
            'description' => '내 정보 수정',
            'is_show' => 1,
        ])->update($input);

        return to_route('app.my.profile.show')->with('message', ['success', __('messages.app.common.success', ['name' => '내 정보'])]);
    }

    /**
     * 소셜연동 toggle
     * GET | app/my/social/{drive} | app.my.social.update
     * @param $driver
     * @return RedirectResponse
     */
    public function updateSocial($driver): RedirectResponse
    {
        $user = auth()->user();

        // 이미 등록되어 있다면 해지 후 프로필 페이지로
        if ($user->{$driver.'_id'}) {
            $driverList = ['naver', 'kakao'];
            $driverCount = 0;
            foreach ($driverList as $value) {
                if ($user->{$value.'_id'}) {
                    $driverCount++;
                }
            }

            // 소셜 회원가입 유저인데 연동 계정이 하나일 경우에는 해제 불가능
            if (!$user->access_id && $driverCount == 1) {
                return to_route('app.my.profile.show')->with('message', ['error', __('messages.app.students.required_social_account')]);
            }

            $name = '소셜연동해제('.$driver.')';
            $user->setActivitylogOptions([
                'description' => $name,
                'is_show' => 1,
            ])->update([
                $driver.'_id' => ''
            ]);

            return to_route('app.my.profile.show')->with('message', ['success', __('messages.app.common.success', ['name' => $name])]);
        } else { // 등록이 안되어있다면 연동페이지로
            session(['social_redirect_url' => route('app.my.profile.show')]);
            return to_route('auth.social.login', [
                $driver
            ]);
        }
    }

    /**
     * 학년학기변경 처리
     * PATCH | app/my/grade-term | app.my.grade-term.update
     * @param StudentGrandAndTermRequest $request
     * @return RedirectResponse
     */
    public function updateGradeAndTerm(StudentGrandAndTermRequest $request): RedirectResponse
    {
        $input = $request->validated();

        /** @var Student $user */
        $user = auth()->user();

        if ($user->isFree() && $user->grade) {
            return to_route('app.my.profile.show')->with('message', ['error', __('messages.app.students.free_cannot_update_semester')]);
        }

        $user->grade = $input['grade'];
        $user->term = $input['term'];
        $user->setActivitylogOptions([
            'is_show' => 1,
            'description' => '학년학기설정',
        ])->save();

        return to_route('app.main')->with('message', ['success', __('messages.app.common.success', ['name' => '학년학기설정'])]);
    }

    /**
     * 프로필 이미지 변경 처리
     * PATCH | app/my/profile-image | app.my.profile-image.update
     * @return RedirectResponse
     */
    public function updateProfileImage(): RedirectResponse
    {
        request()->validate([
            'profile_img_type' => 'required|strict_integer',
        ]);

        /** @var Student $user */
        $user = auth()->user();

        $user->setActivitylogOptions([
            'description' => '프로필 이미지 변경',
            'is_show' => 1,
        ])->update(['profile_img_type' => request('profile_img_type')]);

        return to_route('app.my.profile.show')->with('message', ['success', __('messages.app.common.success', ['name' => '프로필 이미지 변경'])]);
    }


    /**
     * 결제 목록
     *
     * GET | app/my/payments | app.my.payments.index
     * @return Response|RedirectResponse
     */
    public function indexPayments(): Response|RedirectResponse
    {
        /** @var Student $user */
        $user = auth()->user();

        $products = config('dailykor.payment.products');
        $payments = $user->payments;

        return Inertia::render('my/payments/Index', [
            'products' => $products,
            'payments' => $payments,
            'config' => [
                'dbcode' => [
                    'payments' => config('dailykor.dbcode.payments'),
                ],
            ],
        ]);
    }

    /**
     * 결제 상세
     *
     * GET | app/my/payments/{payment} | app.my.payments.show
     * @param Payment $payment
     * @return Response
     */
    public function showPayments(Payment $payment): Response|RedirectResponse
    {
        return Inertia::render('my/payments/Show', [
            'payment' => $payment,
            'route_name' => request()->route()->getName(),
        ]);
    }

    /**
     * 마이페이지 회원탈퇴
     * @return void
     */
    public function showWithdraw(): Response|RedirectResponse
    {
        return Inertia::render('my/profile/Withdraw', [
            'route_name' => request()->route()->getName(),
        ]);
    }

    public function updateWithdraw(): JsonResponse
    {
        try {
            $withdrawReason = request()->input('withdraw_reason');
            $etc = request()->input('etc');
            $user = auth()->user();

            if ($user->academy_id) {
                throw new Exception(__('messages.app.students.withdraw_academy'), 403);
            }

            if ($withdrawReason && $etc) {
                $withdrawReason = $etc;
            }

            $extra['withdraw'] = [
                'reason' => $withdrawReason ?? '',
                'date' => date('Y-m-d H:i:s'),
            ];

            $user->setActivitylogOptions([
                'description' => '회원 탈퇴',
                'is_show' => 1,
            ])->update([
                'status' => StudentStatusEnum::WITHDRAW,
                'name' => 'xxx',
                'parents_name' => 'xxx',
                'phone' => '00000000000',
                'parents_phone' => '00000000000',
                'school_name' => 'xxx',
                'naver_id' => null,
                'kakao_id' => null,
                'extra' => $extra
            ]);

            auth()->logout();

            return $this->sendResponse([], '안녕히가세요.');
        } catch (Exception $e) {
            return $this->sendError('탈퇴 실패', $e->getMessage(), $e->getCode());
        }
    }
}
