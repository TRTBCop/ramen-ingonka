<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfGradeNotSet
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // 학년과 학기가 설정되지 않았다면 설정 페이지로 리디렉션
        if ($request->isMethod('GET') && !$request->routeIs('app.main.grade-term') && (!$user->grade || !$user->term)) {
            return to_route('app.main.grade-term')->with('message', ['success', __('messages.app.students.grade_semester_set')]);
        }

        return $next($request);
    }
}
