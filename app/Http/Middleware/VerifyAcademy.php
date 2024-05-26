<?php

namespace App\Http\Middleware;

use App\Models\Academy;
use Closure;
use Illuminate\Http\Request;

class VerifyAcademy
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
        if (auth()->user() instanceof Academy) {
            return $next($request);
        } else {
            return redirect(route('admin.login'));
        }
    }
}
