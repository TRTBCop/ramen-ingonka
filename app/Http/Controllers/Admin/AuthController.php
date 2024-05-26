<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Auth\AdminRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class AuthController extends BaseController
{
    /*
     * 로그인
     * GET|HEAD | admin/login | admin.login
     */
    public function index(): Response
    {
        return Inertia::render('auth/Login');
    }

    /*
     *  관리자 마이페이지
     * GET admin/profile ...................................................... admin.profile.show
     */
    public function profile(Request $request): Response
    {
        $request->user()->load('roles');

        return Inertia::render('auth/Profile', [
            'page' => [
                'title' => '내 프로필정보',
                'breadcrumbs' => [],
            ],
        ]);
    }

    /*
     * 관리자 마이페이지 수정
     * PUT|PATCH admin/profile ...................................................... admin.profile.update
     */
    public function profileUpdate(AdminRequest $request): RedirectResponse
    {
        if ($request->remove_avatar) {
            $request->avatarRemove();
        }
        $request->avatarUpload();

        $input = $request->all();
        if (isset($input['password'])) {
            $input['password'] = bcrypt($input['password']);
        }

        $request->user()->fill($input)->setActivitylogOptions([
            'description' => '내 프로필정보수정',
            'is_show' => 1,
        ])->save();

        return to_route('admin.profile.show')->with('저장 ');
    }

    /**
     * 로그인 처리
     *
     * POST | admin/login | admin.login.store
     *
     * @param AdminRequest $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(AdminRequest $request): RedirectResponse
    {
        $request->authenticate();
        $admin = auth('admin')->user();

        activity()->causedBy($admin)->event('login')->log('login_success');

        if ($admin->hasRole(['super', 'manager', 'contents', 'cs'])) {
            return to_route('admin.dashboard')->with('message', ['success', '반갑습니다.']);
        } else {
            return to_route('admin.login')->with('message', ['error', __('messages.error_403')]);
        }
    }


    /**
     * 로그아웃
     *
     *  POST | admin/logout | admin.logout
     */
    public function destroy(Request $request): RedirectResponse
    {
        auth('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return to_route('admin.login');
    }
}
