<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class SettingController extends BaseController
{
    public function __construct(
        public string $name = '환경설정',
    ) {
        $this->middleware(['permission:academy']);
    }

    /**
     * GET|HEAD | admin/settings/policy | admin.settings.policy.show
     * @return Response
     */
    public function policyShow(): Response
    {
        return Inertia::render('settings/policy/Show', [
            'agree' => setting('agree'),
            'privacy' => setting('privacy'),
            'marketing' => setting('marketing'),
            'page' => [
                'active' => 'admin.settings.show',
                'title' => '이용약관관리',
                'breadcrumbs' => ['환경설정'],
            ],
        ]);
    }

    /**
     * PUT|PATCH | admin/settings/policy | admin.settings.policy.update
     * @return RedirectResponse
     */
    public function policyUpdate(): RedirectResponse
    {
        if (setting('agree') != request()->agree) {
            setting('agree', request()->agree);
        }

        if (setting('privacy') != request()->privacy) {
            setting('privacy', request()->privacy);
        }

        if (setting('marketing') != request()->marketing) {
            setting('marketing', request()->marketing);
        }

        return to_route('admin.settings.policy.show')->with('message', ['success', '저장성공']);
    }
}
