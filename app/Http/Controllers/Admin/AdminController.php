<?php

namespace App\Http\Controllers\Admin;

use App\Enums\RoleEnum;
use App\Http\Controllers\BaseController;
use App\Http\Requests\AdminRequest;
use App\Models\Admin;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @group Admin 관리자관리
 * @authenticated
 *
 * APIs for managing users
 */
class AdminController extends BaseController
{
    public function __construct(
        public string $name = '운영자',
    ) {
        $this->middleware(['permission:manager']);
    }

    /**
     * 목록
     * GET|HEAD | admin/admins | admin.admins.index
     *
     */
    public function index(): Response
    {
        $collection = $this->getCollection(Admin::with('roles'));


        return Inertia::render('admins/Index', [
            'collection' => $collection,
            'route_name' => request()->route()->getName(),
            'roles' => collect(RoleEnum::admin())->map(fn ($v) => [
                'text' => $v->text(),
                'name' => $v->name,
                'value' => $v->value,
            ]),
            'page' => [
                'active' => 'admin.admins.index',
                'title' => $this->name.'목록',
                'breadcrumbs' => ['운영자관리'],
            ],
        ]);
    }

    /**
     * 등록
     *
     * POST | admin/admins | admin.admins.store
     *
     * @param AdminRequest $request
     * @return RedirectResponse
     */
    public function store(AdminRequest $request): RedirectResponse
    {
        return parent::setStore($this, new Admin(), $request);
    }


    /**
     * 등록 폼
     *
     * GET|HEAD | admin/admins/create | admin.admins.create
     */
    public function create(): Response
    {
        return Inertia::render('admins/Create', [
            'roles' => collect(RoleEnum::admin())->map(fn ($v) => [
                'text' => $v->text(),
                'name' => $v->name,
                'value' => $v->value,
            ]),
            'page' => [
                'active' => 'admin.admins.index',
                'title' => $this->name.' 등록',
                'breadcrumbs' => ['운영자관리'],
            ],
        ]);
    }


    /**
     * 상세
     *
     * GET|HEAD | admin/admins/{admin} | admin.admins.show
     */
    public function show(Admin $admin): Response
    {
        $admin->append('avatar')->load('roles');
        return Inertia::render('admins/Show', [
            'admin' => $admin,
            'roles' => collect(RoleEnum::admin())->map(fn ($v) => [
                'text' => $v->text(),
                'name' => $v->name,
                'value' => $v->value,
            ]),
            'page' => [
                'active' => 'admin.admins.index',
                'title' => $this->name.' 상세',
                'breadcrumbs' => ['운영자관리'],
            ],
        ]);
    }


    /**
     * 수정
     *
     * PUT|PATCH | admin/admins/{branch} | admin.admins.update
     */
    public function update(AdminRequest $request, Admin $admin): RedirectResponse
    {
        $messageType = $request->isMethod('POST') ? '등록' : '수정';

        $input = $request->all();
        if (isset($input['password']) && $input['password']) {
            $input['password'] = bcrypt($input['password']);
        } else {
            unset($input['password']);
        }

        $admin->fill($input)->setActivitylogOptions([
            'description' => $this->name.'가 '.$messageType.'되었습니다.',
            'is_show' => 1,
        ])->save();

        $arrRole = empty(request()->roles) ? [] : request()->roles;
        $admin->syncRoles($arrRole);

        // 프로필이미지 등록/삭제
        if (request()->remove_avatar) {
            $admin->avatarRemove();
        }
        $admin->avatarUpload();

        $admin->refresh();
        return to_route('admin.admins.show', $admin->id)->with('message', ['success', '저장성공']);
    }

    /**
     * 삭제
     *
     * DELETE | admin/admins/{admin} | admin.admins.destroy
     * @param Admin $admin
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Admin $admin): RedirectResponse
    {
        $message = $this->name.' '.$admin->name.'삭제';
        $admin->setActivityLogOptions([
            'description' => $message,
        ])->delete();

        return to_route('admin.admins.index')->with('message', ['success', $message.'성공']);
    }
}
