<?php

use App\Http\Controllers\Admin\AcademyController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BoardNoticeController;
use App\Http\Controllers\Admin\CurriculumController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\TestController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/login', [AuthController::class, 'index'])->name('admin.login'); // 로그인
Route::post('/login', [AuthController::class, 'store'])->name('admin.login.store'); // 로그인 처리


Route::middleware(['auth:admin', 'verified'])->name('admin.')->group(function () {
    Route::get('profile', [AuthController::class, 'profile'])->name('profile.show'); // 내정보수정
    Route::match(['put', 'patch'], '/profile', [AuthController::class, 'profileUpdate'])->name('profile.update'); // 내정보수정 UPDATE
    Route::post('logout', [AuthController::class, 'destroy'])->name('logout'); // 로그아웃
    Route::get('', [DashboardController::class, '__invoke'])->name('dashboard'); //대시보드


    Route::resources([
        'admins' => AdminController::class, // 관리자
        'academies' => AcademyController::class, // 학원
        'board-notices' => BoardNoticeController::class, // 게시판-공지사항
        'students' => StudentController::class, // 학생
        'questions' => QuestionController::class, // 문제관리
    ]);

    Route::prefix('academies')->name('academies.')->group(function () {
        Route::get('{academy}/student-list', [AcademyController::class, 'studentList'])->name('student-list'); // 학생 결제목록
    });

    // php artisan route:list --name=students --columns=method,uri,name,action
    Route::prefix('students')->name('students.')->group(function () {
        Route::get('{student}/payments/{payment}', [PaymentController::class, 'studentShow'])->name('payments.show'); // 학생 결제목록
        Route::get('{student}/payments', [PaymentController::class, 'studentIndex'])->name('payments.index'); // 학생 결제목록
        Route::get('{student}/active-log', [StudentController::class, 'activeLog'])->name('active-log'); // 학생 활동로그
        Route::get('{student}/change-log', [StudentController::class, 'changeLog'])->name('change-log'); // 학생 변경로그
        Route::get('{student}/learning-history', [StudentController::class, 'learningHistory'])->name('learning-history'); // 학생 학습내역
        Route::get('{student}/learning-report', [StudentController::class, 'learningReport'])->name('learning-report'); // 학생 학습보고서
        Route::get('{student}/login', [StudentController::class, 'login'])->name('login'); // 관리자 학생로그인
    });
    Route::get('students-export', [StudentController::class, 'export'])->name('students.export'); // 학생 액셀내보내기

    Route::resource('payments', PaymentController::class)->except(['edit']); // 결제

    Route::get('b2c-payments', [PaymentController::class, 'b2cIndex'])->name('b2c-payments.index'); // b2c 결제목록

    Route::get('academies-export', [AcademyController::class, 'export'])->name('academies.export'); // 학원 액셀내보내기
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::post('{payment}/cancel', [PaymentController::class, 'cancel'])->name('cancel');
        Route::post('cash-receipt/{payment}', [PaymentController::class, 'cashReceipt'])->name('cash-receipt');
    });

    // 커리큘럼 관리
    Route::prefix('curricula')->name('curricula.')->group(function () {

        // index
        Route::get('/', [CurriculumController::class, 'index'])->name('index');

        // nestedSet
        Route::get('/nested-set', [CurriculumController::class, 'nestedSet'])->name('nested-set');

        // 개념-지문생성
        Route::post('{curriculum}/1/texts', [CurriculumController::class, 'storeTrainingConceptTexts'])->name('texts.create'); //삭제

        // 개념-지문삭제
        Route::delete('{curriculum}/1/texts/{trainingConceptText}', [CurriculumController::class, 'destroyTrainingConceptTexts'])->name('texts.destroy'); //삭제

        // 개념훈련-개념읽기|개념요약|개념다지기
        Route::get('/{curriculum}/1/texts/{trainingConceptText}/{type}', [CurriculumController::class, 'showTraining1'])->name('training1.texts.show');
        Route::match(['put', 'patch'], '/{curriculum}/1/texts/{trainingConceptText}/{type}', [CurriculumController::class, 'updateTraining1'])
            ->name('training1.texts.update');

        // 개념훈련-기초연산
        Route::get('/{curriculum}/1/operations', [CurriculumController::class, 'showTraining1Operations'])->name('training1.operations.show');
        Route::match(['put', 'patch'], '/{curriculum}/1/operations', [CurriculumController::class, 'updateTraining1Operations'])->name('training1.operations.update');

        // 트레이닝 상세
        Route::get('/{curriculum}/{trainingStage}', [CurriculumController::class, 'showTraining'])
            ->where(['trainingStage' => '[1-3]'])
            ->name('trainings.show');

        // 트레이닝 상세 저장
        Route::match(['put', 'patch'], '{curriculum}/{trainingStage}', [CurriculumController::class, 'updateTraining'])
            ->where(['trainingStage' => '[1-3]'])
            ->name('trainings.update');
    });

    // 학습 조회 | 등록 | 수정 | 삭제
    Route::resource('curricula', CurriculumController::class)->except(['edit']);

    Route::prefix('tests')->name('tests.')->group(function () {
        Route::get('/', [TestController::class, 'index'])->name('index'); //목록
        Route::get('/{test}', [TestController::class, 'show'])->name('show'); //상세
        Route::match(['put', 'patch'], '{test}', [TestController::class, 'update'])->name('update');
        Route::delete('{test}', [TestController::class, 'destroy'])->name('destroy'); //삭제

        Route::prefix('{test}/questions')->name('questions.')->group(function () {
            Route::post('/', [TestController::class, 'questionStore'])->name('store'); //문제등록
            Route::get('{question}', [TestController::class, 'questionShow'])->name('show'); //문제상세
            Route::match(['put', 'patch'], '{question}', [TestController::class, 'questionUpdate'])->name('update');
        });
    });

    /*
    Route::prefix('units')->name('units.')->group(function () {
        Route::get('/', [UnitController::class, 'index'])->name('index'); // 목록
        Route::post('/store', [UnitController::class, 'store'])->name('store'); // 등록
        Route::get('/{unit}/{trainingStage}', [UnitController::class, 'show'])
            ->where(['unit' => '[0-9]+', 'trainingStage' => '[1-4]'])
            ->name('show'); // 상세
    });
    */

    // 환경설정
    Route::get('settings/policy', [SettingController::class, 'policyShow'])->name('settings.policy.show');
    Route::match(['put', 'patch'], 'settings/policy', [SettingController::class, 'policyUpdate'])->name('settings.policy.update');
});
