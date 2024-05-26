<?php

namespace App\Http\Controllers\App;

use App\Http\Requests\Auth\StudentRequest;
use App\Http\Controllers\BaseController;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AuthController extends BaseController
{
    /**
     * 로그인 form
     * GET|HEAD | app/login | app.auth.create
     *
     * @return Response
     */
    public function create(): Response
    {
        return Inertia::render('auth/Login', [
            'social_url' => [
                'naver' => route('auth.social.login', 'naver'),
                'kakao' => route('auth.social.login', 'kakao')
            ]
        ]);
    }

    /**
     * 로그인 form
     * GET|HEAD | app/login | app.auth.create
     *
     * @return Response
     */
    public function createOther(): Response
    {
        return Inertia::render('auth/OtherLogin', []);
    }

    /**
     * POST | app/login | app.auth.store
     *
     * @param StudentRequest $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(StudentRequest $request): \Symfony\Component\HttpFoundation\Response
    {
        $request->authenticate();

        return to_route('app.main')->with('message', ['success', '반갑습니다.']);
    }


    /**
     * 로그아웃
     * POST | app/logout | app.auth.destroy
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(): \Illuminate\Http\RedirectResponse
    {
        auth()->logout();
        return to_route('app.auth.create')->with('message', ['success', '안녕하가세요.']);
    }

    /**
     * 소셜로그인
     * GET|HEAD | auth/social/{driver} | auth.social.login
     *
     * @param $driver
     * @return RedirectResponse|\Illuminate\Http\RedirectResponse
     */
    public function social($driver): RedirectResponse|\Illuminate\Http\RedirectResponse
    {
        if (request('redirect') && request()->route()->has(request('redirect'))) {
            session(['social_redirect_route' => request('redirect')]);
        } else {
            session(['social_redirect_route' => '']);
        }

        return Socialite::driver($driver)->redirect();
    }

    /**
     * 소셜로그인 콜백
     *
     * @param $driver
     * @return \Illuminate\Http\RedirectResponse
     */
    public function socialCallback($driver): \Illuminate\Http\RedirectResponse
    {
        $socialUser = Socialite::driver($driver)->user();

        $user = auth()->user();

        // 로그인되어있는 사용자라면 마이페이지로 이동
        if ($user) {
            $name = '소셜연동('.$driver.')';
            $user->setActivitylogOptions([
                'description' => $name,
                'is_show' => 1,
            ])->update([
                $driver.'_id' => $socialUser->id
            ]);
            return to_route('app.my.profile.show')->with('message', ['success', $name]);
        } else {
            $user = Student::where([
                $driver.'_id' => $socialUser->id
            ])->first();

            if ($user) {
                // 로그인후 홈으로 이동
                Auth::login($user);
                $user->last_login_at = now();
                $user->setActivitylogOptions([
                    'is_show' => 1,
                    'description' => '소셜로그인성공',
                ])->save();
                return to_route('app.main')->with('message', ['success', '반갑습니다.']);
            }

            // 연동 안되어있는 회원이라면 회원가입으로 이동
            session()->flash($driver.'_id', $socialUser->id);
            return to_route('app.register.create')->with('message', ['success', '소셜회원가입']);
        }
    }

    /**
     * 토큰 로그인
     * GET|HEAD | app/token-login/{token} | app.auth.token-login
     *
     * @return RedirectResponse
     */
    public function tokenLogin(string $token): RedirectResponse
    {
        [$id, $userToken] = explode('|', $token, 2);
        $tokenData = DB::table('personal_access_tokens')->where('token', hash('sha256', $userToken))->first();

        $isExpired = true;

        if (isset($tokenData)) {
            $user = app($tokenData->tokenable_type)->find($tokenData->tokenable_id);
            $abilities = ((array)(json_decode($tokenData->abilities)));

            // 기간만료
            $isExpired = isset($abilities['expires_in']) && Carbon::parse($tokenData->updated_at)->diffInSeconds() > $abilities['expires_in'];

            // 유저의 학생 유무
            $isStudent = $user instanceof Student;
        }

        if ($isExpired || !$isStudent) {
            return to_route('app.auth.create')->with('message', ['error', '유효하지 않은 토큰입니다.']);
        }

        auth('web')->login($user);
        activity(request()->route()->getName())
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->log('토큰-학생로그인');

        // 토큰을 삭제
        $user->tokens()->where('id', $id)->delete();

        return to_route('app.main')->with('message', ['success', '반갑습니다.']);
    }
}
