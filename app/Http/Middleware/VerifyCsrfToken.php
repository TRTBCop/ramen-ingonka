<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        '/app/upload/image',
        '/app/payments/result',
        '/app/payments/show-result',
        '/app/payments/noti/*',
        '/app/payments/next',
        '/app/payments/canc',
    ];
}
