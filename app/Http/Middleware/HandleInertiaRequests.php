<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'brand';

    public function rootView(Request $request): string
    {
        if (request()->is('admin/*') or request()->is('admin')) {
            return 'admin';
        }

        if (request()->is('academy/*') or request()->is('academy')) {
            return 'academy';
        }

        if (request()->is('bank/*') or request()->is('bank')) {
            return 'bank';
        }

        if (request()->is('html/*') or request()->is('html')) {
            return 'html';
        }

        if (request()->is('app/*') or request()->is('app')) {
            return 'app';
        }

        return parent::rootView($request);
    }

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'is_guest' => auth()->guest(),
                'is_student' => auth('web')->check(),
                'is_academy' => auth('academy')->check(),
                'user' => $request->user()?->append(['avatar', 'role_names']),
                'csrf' => $request->session()->token(),
            ],
            'config' => [
                'service' => [
                    'company' => config('dailykor.service.company'),
                ],
            ],
            'messages' => request()->is('app/*') ? __('messages.app') : [],
            'flash' => [
                'message' => fn () => $request->session()->get('message'),
            ],
        ]);
    }
}
