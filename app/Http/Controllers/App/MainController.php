<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\BaseController;
use App\Models\Student;
use App\Services\MainService;
use App\Services\StudentService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class MainController extends BaseController
{
    public function __construct()
    {
    }


    /**
     * 표준모드
     * GET|HEAD | app | app.main
     * GET|HEAD | app/main | app.main
     * @return RedirectResponse|Response
     */
    public function show(): Response|RedirectResponse
    {
        /** @var Student $user */
        $user = auth()->user();

        // 무료 체험 종료 체크
        (new StudentService())->checkExpiredFree($user);

        $mainService = new MainService($user);

        // 다음 훈련
        $nextTraining = $mainService->getNextTraining();

        // 대단원 진행률
        $progressByGradeTermCurriculums = $mainService->getCurriculumsProgressByGradeTerm();

        return Inertia::render('main/Show', [
            'progress_by_grade_term_curriculums' => $progressByGradeTermCurriculums,
            'next_training' => $nextTraining,
            'config' => [
                'dbcode' => [
                    'curricula' => dbcode('curricula'),
                    'students' => dbcode('students'),
                    'trainings' => dbcode('trainings')
                ],
            ],
        ]);
    }

    /**
     * 자유모드
     * GET|HEAD | app/main/free | app.main.free
     * @return Response
     */
    public function showFree(): Response
    {
        /** @var Student $user */
        $user = auth()->user();

        $mainService = new MainService($user);

        // 전체 학습
        $curriculumMap = $mainService->getcurriculaMap();


        return Inertia::render('main/Free', [
            'curricula_map' => $curriculumMap,
            'config' => [
                'dbcode' => [
                    'curricula' => dbcode('curricula'),
                    'students' => dbcode('students'),
                    'trainings' => dbcode('trainings')
                ],
            ],
        ]);
    }

    /**
     * 학년/학기 선택모드
     * GET|HEAD | app/main/grade-term | app.main.grade-term
     * @return Response
     */
    public function gradeAndTerm(): Response
    {
        return Inertia::render('main/GradeTerm', [
            'config' => [
                'training' => [
                    'grade_group' => config('dailykor.training.grade_group')
                ],
                'dbcode' => [
                    'students' => dbcode('students'),
                ],
            ],
        ]);
    }
}
