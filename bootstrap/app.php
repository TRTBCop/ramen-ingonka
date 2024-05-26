<?php

use App\Http\Middleware\VerifyAcademy;
use App\Http\Middleware\VerifyAdmin;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->use([
            // \Illuminate\Http\Middleware\TrustHosts::class,
            \Illuminate\Http\Middleware\TrustProxies::class,
            \Illuminate\Http\Middleware\HandleCors::class,
            \Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance::class,
            \Illuminate\Http\Middleware\ValidatePostSize::class,
            \Illuminate\Foundation\Http\Middleware\TrimStrings::class,
            //\Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        ]);

        $middleware->group('web', [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);

        $middleware->group('api', [
            \Illuminate\Routing\Middleware\ThrottleRequests::class.':api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        $middleware->alias([
            'auth' => \App\Http\Middleware\Authenticate::class,
            'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
            'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
            'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
            'can' => \Illuminate\Auth\Middleware\Authorize::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
            'signed' => \App\Http\Middleware\ValidateSignature::class,
            'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
            'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,

            // role or permission
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,

            'admin' => VerifyAdmin::class,
            'academy' => VerifyAcademy::class,
            'dev.only' => \App\Http\Middleware\EnsureIsDevelopment::class,
            'grade.set' => \App\Http\Middleware\RedirectIfGradeNotSet::class, // 학년학기 확인
            'service' => \App\Http\Middleware\RedirectIfServiceNotActive::class, // 유저의 서비스 사용 유무 확인
            'free_denied' => \App\Http\Middleware\RedirectIfFreeUser::class, // 무료 체험 접근 거부:

            'log.requests' => \App\Http\Middleware\LogRequestResponse::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->respond(function (Response $response, Throwable $e, Request $request) {
            $status = $response->getStatusCode();
            $messages = [
                500 => '시스템에 문제가 발생했습니다.',
                503 => '서비스 이용불가',
                404 => '죄송합니다.요청하신 페이지를 찾을수 없습니다.',
            ];

            $description = [
                500 => '기술적인 문제로 일시적으로 접속이 원활하지 않습니다.<br>
                문제를 해결하기 위해 열심히 노력하고 있으니<br>
                잠시 후 다시 확인해 주세요.
                ',
                503 => '서비스 이용불가',
                404 => '방문하시려는 페이지의 주소가 잘못 입력되었거나,<br>
                페이지의 주소가 변경 혹은 삭제되어 요청하신 페이지를 찾을 수 없습니다.<br>
                입력하신 주소가 정확한지 다시 한번 확인해 주세요.
                ',
            ];

            // json 요청이라면
            if ($request->wantsJson()) {
                $ret = [];
                if ($e instanceof ModelNotFoundException) {
                    $ret = ['페이지를 찾을수 없습니다. | ModelNotFound', 404];
                } elseif ($e instanceof NotFoundHttpException) {
                    $ret = ['페이지를 찾을수 없습니다. | NotFoundHttp', 404];
                } elseif ($e instanceof AuthenticationException) {
                    $ret = ['인증실패 | Authentication', 401];
                } elseif ($e instanceof AuthorizationException) {
                    $ret = ['권한없음 | Authorization', 403];
                } elseif ($e instanceof MethodNotAllowedHttpException) {
                    $ret = ['Method not allowed.', 405];
                }

                return response()->json([
                    'success' => false,
                    'message' => $ret[0] ?? $e->getMessage(),
                    'errors' => ($e instanceof ValidationException) ? $e->validator->getMessageBag() : ''
                ], $ret[1] ?? $status);
            }

            session()->flash('message', ['error', '문제가 발생했습니다.']);

            if (!array_key_exists($status, $messages)) {
                return $response;
            }

            if (!$request->isMethod('GET')) {
                return back()->with('error', $messages[$status]);
            }

            return inertia('errors/Index', [
                'status' => $status,
                'message' => $messages[$status] ?? '문제가 발생했습니다.',
                'description' => $description[$status] ?? '',
            ])
                ->toResponse($request)
                ->setStatusCode($status);
        });
    })->create();
