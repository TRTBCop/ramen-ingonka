<?php

use App\Http\Controllers\App\AuthController;
use App\Http\Controllers\Brand\ContentsController;
use App\Http\Controllers\Brand\PriceController;
use App\Http\Controllers\Brand\SpecController;
use App\Http\Controllers\Brand\WelcomeController;
use App\Http\Controllers\Brand\PolicyController;
use App\Http\Controllers\HtmlController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::name('brand.')->group(function () {
    Route::get('', WelcomeController::class)->name('index');

    // 콘텐츠 소개
    Route::get('contents', [ContentsController::class, 'index'])->name('contents.index');

    // 가격 안내
    Route::get('price', [PriceController::class, 'index'])->name('price.index');

    // 사양 안내
    Route::get('spec', [SpecController::class, 'index'])->name('spec.index');

    Route::prefix('policy')->name('policy.')->group(function () {
        Route::get('terms', [PolicyController::class, 'terms'])->name('terms'); // 서비스이용약관
        Route::get('privacy', [PolicyController::class, 'privacy'])->name('privacy'); // 개인정보 처리방침
    });
});


Route::get('auth/social/{driver}', [AuthController::class, 'social'])
    ->where('driver', implode('|', array_keys(config('dailykor.code.social'))))
    ->name('auth.social.login');
Route::get('auth/social/{driver}/callback', [AuthController::class, 'socialCallback'])
    ->where('driver', implode('|', array_keys(config('dailykor.code.social'))));


// 개발 환경에서만 접근
Route::middleware('dev.only')->group(function () {
    Route::get('/html/{page?}', HtmlController::class);
});
