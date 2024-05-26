<?php

use App\Http\Controllers\App\BoardNoticeController;
use App\Http\Controllers\App\ComingSoonController;
use App\Http\Controllers\App\TrainingController;
use App\Http\Controllers\App\AuthController;
use App\Http\Controllers\App\IncorrectNoteController;
use App\Http\Controllers\App\MainController;
use App\Http\Controllers\App\MyController;
use App\Http\Controllers\App\PaymentController;
use App\Http\Controllers\App\RegisterController;
use App\Http\Controllers\App\ReportController;
use App\Http\Controllers\App\TestController;
use App\Http\Controllers\App\TrainingHistoryController;
use App\Http\Controllers\App\TrainingResultsController;
use App\Http\Controllers\App\IncorrectExplanationController;
use App\Http\Controllers\App\UploadController;
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

// 회원가입, 아이디 찾기, 비밀번호 찾기
Route::prefix('register')->name('register.')->group(function () {
    Route::get('', [RegisterController::class, 'create'])->name('create'); // 회원가입
    Route::post('', [RegisterController::class, 'store'])->name('store'); // 회원가입 처리
    Route::get('result', [RegisterController::class, 'result'])->name('result'); // 회원가입완료

    Route::post('verification-code-send', [RegisterController::class, 'verificationCodeSend'])->name('verification-code-send'); // 휴대폰 인증번호 발송
    Route::post('verification-code-check', [RegisterController::class, 'verificationCodeCheck'])->name('verification-code-check'); // 휴대폰 인증번호 확인

    Route::get('check-account', [RegisterController::class, 'checkAccount'])->name('check-account'); // 아이디 확인

    Route::get('find-account', [RegisterController::class, 'showFindAccount'])->name('find-account'); // 아이디 찾기
    Route::post('find-account', [RegisterController::class, 'findAccount'])->name('find-account.store'); // 아이디 찾기

    Route::get('find-password', [RegisterController::class, 'showFindPassword'])->name('find-password'); // 비밀번호 찾기
    Route::post('find-password', [RegisterController::class, 'findPassword'])->name('find-password.store'); // 비밀번호 찾기
    Route::post('find-password-reset', [RegisterController::class, 'findPasswordReset'])->name('find-password-reset'); // 비밀번호 재설정
});

// 로그인
Route::get('login', [AuthController::class, 'create'])->name('auth.create'); // 학생로그인
Route::get('token-login/{token}', [AuthController::class, 'tokenLogin'])->name('auth.token-login'); // 토큰로그인
Route::get('outherLogin', [AuthController::class, 'createOther'])->name('auth.other.create'); // 학생로그인
Route::post('login', [AuthController::class, 'store'])->name('auth.store'); // 로그인


Route::post('payments/noti/{studentId}', [PaymentController::class, 'noti'])->name('payments.noti');  // b2c 결제 처리
Route::post('payments/next', [PaymentController::class, 'next'])->name('payments.next');  // 결제 완료 화면
Route::post('payments/canc', [PaymentController::class, 'canc'])->name('payments.canc');  // 결제 취소 화면

Route::prefix('upload')->group(function () {
    Route::post('/image', [UploadController::class, 'imageStore'])->name('upload.image');
});

// 관리자 접근
Route::middleware(['auth:admin', 'web'])->group(function () {
    // 훈련 미리보기
    Route::prefix('trainings/{training}/preview/')->name('trainings.')->group(function () {
        // 개념훈련 - 개념학습
        Route::prefix('/1/texts')->name('stage1.texts.')->group(function () {
            // 개념훈련 - 개념학습 - 개념읽기
            Route::get('/{trainingConceptText}/readings', [TrainingController::class, 'showStage1TextsReadings'])
            ->name('readings.preview.show');
            // 개념훈련 - 개념학습 - 개념요약
            Route::get('/{trainingConceptText}/summarizations/{question?}', [TrainingController::class, 'showStage1TextsSummarizations'])
            ->name('summarizations.preview.show');
            // 개념훈련 - 개념학습 - 개념다지기
            Route::get('/{trainingConceptText}/reinforcements/{question?}', [TrainingController::class, 'showStage1TextsReinforcements'])
            ->name('reinforcements.preview.show');
        });

        // 개념훈련 - 기초연산
        Route::get('/1/operations/{question?}', [TrainingController::class, 'showStage1Operations'])
        ->name('stage1.operations.preview.show');

        // 유형훈련
        Route::get('/2/{step}/{question?}', [TrainingController::class, 'showStage2'])->where('step', '[0-3]')
        ->name('stage2.preview.show');

        // 서술형훈련
        Route::get('/3/{step}/{question?}', [TrainingController::class, 'showStage3'])->where('step', '[0-2]')
        ->name('stage3.preview.show');

        // 공통 - 결과 전송
        Route::post('/submit', [TrainingController::class, 'submit'])
        ->name('submit');
    });

    // 진단평가 미리보기
    Route::get('tests/{test}/preview/{question?}', [TestController::class, 'show'])->name('tests.preview.show'); // 레벨테스트 응시
});

// 회원접근
Route::middleware(['auth', 'web', 'grade.set'])->group(function () {
    // 메인(자유모드)
    Route::get('/main/free', [MainController::class, 'showFree'])->name('main.free');
    // 메인(학년/학기 선택)
    Route::get('/main/grade-term', [MainController::class, 'gradeAndTerm'])->name('main.grade-term');
    // 메인(기본모드)
    Route::get('/', [MainController::class, 'show'])->name('home');
    Route::get('/main', [MainController::class, 'show'])->name('main');


    // 서비스 이용중
    Route::middleware(['service'])->group(function () {
        // 진단평가
        Route::prefix('tests')->name('tests.')->group(function () {
            Route::get('', [TestController::class, 'index'])->name('index');
            Route::get('{test}', [TestController::class, 'show'])->name('show'); // 레벨테스트 응시
            Route::post('{test}', [TestController::class, 'store'])->name('store'); // 레벨테스트 정답제출
            Route::get('{test}/done', [TestController::class, 'done'])->name('done'); // 레벨테스트 완료
            // 시간(timer) 저장
            Route::match(['put', 'patch'], '{test}/timer', [TestController::class, 'updateTimer'])->name('timer.update');
        });


        // 학습 훈련
        Route::prefix('trainings/{training}')->name('trainings.')->group(function () {
            // 훈련 메인
            Route::get('', [TrainingController::class, 'show'])
            ->name('show');

            // 개념훈련 - 개념학습
            Route::prefix('/1/texts')->name('stage1.texts.')->group(function () {
                Route::get('', [TrainingController::class, 'ShowStage1Texts'])
                ->name('show');

                // 개념훈련 - 개념학습 - 개념읽기
                Route::get('/{trainingConceptText}/readings', [TrainingController::class, 'showStage1TextsReadings'])
                ->name('readings.show');

                // 개념훈련 - 개념학습 - 개념요약
                Route::get('/{trainingConceptText}/summarizations', [TrainingController::class, 'showStage1TextsSummarizations'])
                ->name('summarizations.show');

                // 개념훈련 - 개념학습 - 개념다지기
                Route::get('/{trainingConceptText}/reinforcements', [TrainingController::class, 'showStage1TextsReinforcements'])
                ->name('reinforcements.show');

                // 개념훈련 - 개념학습 - 개념정리
                Route::get('/{trainingConceptText}/review', [TrainingController::class, 'showStage1TextsReview'])
                ->name('review.show');
            });

            // 개념훈련 - 기초연산
            Route::get('/1/operations', [TrainingController::class, 'showStage1Operations'])
            ->name('stage1.operations.show');

            // 유형훈련
            Route::get('/2/{step}', [TrainingController::class, 'showStage2'])
            ->where('step', '[0-3]')
            ->name('stage2.show');

            // 서술형훈련
            Route::get('/3/{step}', [TrainingController::class, 'showStage3'])
            ->where('step', '[0-2]')
            ->name('stage3.show');

            // 공통 - 결과 전송
            Route::post('/submit', [TrainingController::class, 'submit'])
            ->name('submit');

            Route::prefix('results/{trainingResult}')->name('results.')->group(function () {
                // 훈련(training) 결과
                Route::get('', [TrainingController::class, 'showResult'])->name('show');

                // 훈련(training) 결과 요약
                Route::get('/summary', [TrainingController::class, 'showResultSummary'])->name('summary.show');

                // 학습(step) 결과
                Route::get('/steps/{stepResult}', [TrainingController::class, 'showStepResult'])
                ->name('steps.show');

                // 학습 오답 해설
                Route::get('/steps/{stepResult}/explanation', [TrainingController::class, 'showStepResultExplanation'])
                ->name('steps.explanation.show');
            });
        });

        Route::prefix('training-results/{trainingResult}')->name('training-results.')->group(function () {
            // 시간(timer) 저장
            Route::match(['put', 'patch'], '/timer', [TrainingResultsController::class, 'updateTimer'])->name('timer.update');
        });

        Route::middleware(['free_denied'])->group(function () {
            // 오답 노트
            Route::prefix('incorrect-note')->name('incorrect-note.')->group(function () {
                Route::get('', [IncorrectNoteController::class, 'index'])->name('index');
                Route::get('/{trainingResult}', [IncorrectNoteController::class, 'show'])->name('show');
            });

            // 학습 기록
            Route::get('training-history/{date?}', [TrainingHistoryController::class, 'index'])->name('training-history.index');
        });
    });

    // 마이페이지
    Route::prefix('my')->name('my.')->group(function () {
        // 내 정보
        Route::get('profile', [MyController::class, 'showProfile'])->name('profile.show');
        Route::match(['put', 'patch'], 'profile', [MyController::class, 'updateProfile'])->name('profile.update');

        // 소셜 로그인
        Route::get('social/{driver}', [MyController::class, 'updateSocial'])
            ->where('driver', implode('|', array_keys(config('dailykor.code.social'))))
            ->name('social.update');

        // 학년 학기 변경
        Route::patch('grade-term', [MyController::class, 'updateGradeAndTerm'])->name('grade-term.update');

        // 프로필 이미지 변경
        Route::patch('profile-image', [MyController::class, 'updateProfileImage'])->name('profile-image.update');

        // 이용권 정보
        Route::get('payments', [MyController::class, 'indexPayments'])->name('payments.index');
        Route::get('payments/{payment}', [MyController::class, 'showPayments'])->name('payments.show');

        //탈퇴
        Route::get('withdraw', [MyController::class, 'showWithdraw'])->name('withdraw.show');
        Route::post('withdraw', [MyController::class, 'updateWithdraw'])->name('withdraw.update');
    });

    // 결제
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('', [PaymentController::class, 'create'])->name('create'); // 이용권 구매
        Route::post('issue-cash-receipt', [PaymentController::class, 'issueCashReceipt'])->name('issue-cash-receipt'); // 현금영수증 발급
        Route::post('{payment}/cancel', [PaymentController::class, 'cancel'])->name('cancel'); // 이용권 결제 취소
        Route::post('encrypt-params', [PaymentController::class, 'encryptParams'])->name('encrypt-params');
        Route::post('result', [PaymentController::class, 'result'])->name('result');
    });

    // 공지사항
    Route::prefix('board-notices')->name('board-notices.')->group(function () {
        Route::get('', [BoardNoticeController::class, 'index'])->name('index');
        Route::get('{id}', [BoardNoticeController::class, 'show'])->name('show');
    });


    Route::resource('reports', ReportController::class)->only(['index', 'show']); // 보고서

    Route::post('logout', [AuthController::class, 'destroy'])->name('auth.destroy'); // 로그아웃
});

// 준비중 페이지
Route::get('/coming-soon', [ComingSoonController::class, 'show'])->name('coming-soon.show');


Route::get('reports/test/{uuid}', [TestController::class, 'showReport'])->name('tests.reports.show');
