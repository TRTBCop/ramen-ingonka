<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{

    private array $guards;

    public function handle($request, Closure $next, ...$guards): mixed
    {
        $this->guards = $guards;
        return parent::handle($request, $next, ...$guards);
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (in_array('admin', $this->guards)) {
            $route = 'admin.login';
        } elseif (in_array('academy', $this->guards)) {
            $route = 'academy.login';
        } else {
            $route = 'app.auth.create';
        }

        return $request->expectsJson() ? null : route($route);
    }
}
