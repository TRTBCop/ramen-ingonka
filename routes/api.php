<?php

use App\Http\Controllers\Api\AcademyController;
use App\Http\Controllers\Api\StudentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::middleware(['auth:sanctum', 'log.requests'])->group(function () {
    // 학생 로그인 url 생성
    Route::get('students/generate-login-url', [StudentController::class, 'generateLoginUrl'])->name('students.generate-login-url');

    // 관리자 & 학원 권한
    Route::middleware(['role:super|manager|owner'])->group(function () {
        // 학생 CRUD
        Route::resources([
            'students' => StudentController::class,
        ]);

        // 학원 CRUD
        Route::prefix('academies')->name('academies.')->group(function () {
            // 관리자 권한
            Route::middleware(['role:super|manager'])->group(function () {
                Route::post('', [AcademyController::class, 'store'])->name('store');
                Route::delete('/{academy}', [AcademyController::class, 'destroy'])->name('destroy');
            });
            // 관리자나 소유자만 접근 가능
            Route::get('/{academy}', [AcademyController::class, 'show'])->name('show');
            Route::put('/{academy}', [AcademyController::class, 'update'])->name('update');
        });
    });
});
