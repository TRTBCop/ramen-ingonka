<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfServiceNotActive
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // 무료 체험 유저가 무료 체험이 종료 되었으면
        if ($user->isFree() && $user->isFreeExpired()) {
            return to_route('app.main')->with('message', ['free_expired', __('messages.app.students.free_expired')]);
        } elseif (!$user->isFree() && $user->isExpired()) {
            return to_route('app.main')->with('message', ['error', __('messages.app.students.service_expired')]);
        }

        return $next($request);
    }
}
