<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogRequestResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $title = 'guest';
        $user = auth()->user();
        if (isset($user)) {
            $title = json_encode(['id' => $user->id, 'model' => get_class($user)]);
        }

        Log::channel('api')->info($title.':', [
            'request' => [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'ip' => $request->ip(),
                'input' => $request->all(),
            ],
            'response' => [
                'status' => $response->status(),
                'content' => $response->content(),
            ]
        ]);

        return $response;
    }
}
