<?php

namespace App\Http\Middleware;

use App\Models\Admin;
use Closure;
use Illuminate\Http\Request;

class VerifyAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (auth()->user() instanceof Admin) {
            return $next($request);
        }

        if (!$request->expectsJson() && $request->inertia()) {
            abort(409, '', ['X-Inertia-Location' => url()->route('login')]);
        }


        if (!$request->expectsJson()) {
            return redirect(route('admin.login'));
        }
        return response()->json(['error' => __('messages.error_401')], 401);
    }
}
