<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsDevelopment
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (app()->environment() !== 'local') {
            // 개발 환경이 아닐 경우 403 응답 반환
            return response('Forbidden', 403);
        }

        return $next($request);
    }
}
