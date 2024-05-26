<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\BaseController;
use App\Models\Curriculum;
use App\Models\Student;
use App\Models\TrainingResult;
use App\Services\IncorrectNoteService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class IncorrectNoteController extends BaseController
{
    /**
     * 오답 노트 목록
     * GET|HEAD | app/incorrect-note | app.incorrect-note.index
     * @param Curriculum|null $curriculum
     * @return RedirectResponse|Response
     */
    public function index(): Response|RedirectResponse
    {
        $view = 'incorrect-note/Index';

        /** @var Student */
        $student = auth()->user();

        $incorrectNoteService = new IncorrectNoteService($student);

        $gradeTermOptions = $incorrectNoteService->getFilterOptionsByGradeTerm();

        $stageOptions = $incorrectNoteService->getFilterOptionsByStage();

        $collection =  $incorrectNoteService->getPaginatedFilteredTrainingResults();

        return Inertia::render($view, [
            'collection' => $collection,
            'grade_term_options' => $gradeTermOptions,
            'stage_options' => $stageOptions,
            'parent_curriculum_id' => request()->filters['parent_curriculum_id'],
            'stage' => is_array(request()->filters['stage']) ? 0 : request()->filters['stage']
        ]);
    }

    /**
     * 오답 노트 (개념훈련 제외)
     * GET|HEAD | app/incorrect-note/{trainingResult} | app.incorrect-note.show
     * @param TrainingResult $trainingResult
     * @return RedirectResponse|Response
     */
    public function show(TrainingResult $trainingResult): Response|RedirectResponse
    {
        $view = 'incorrect-note/Show';

        if ($trainingResult->training->stage === 1) {
            return to_route('app.main')->with('message', ['error', '개념훈련은 오답노트가 없습니다.']);
        }

        /** @var Student */
        $student = auth()->user();

        $incorrectNoteService = new IncorrectNoteService($student);

        $trainingResult->load(['training', 'curriculum.ancestors']);
        $incorrectQuestions = $incorrectNoteService->getIncorrectQuestionByTrainingResult($trainingResult);

        return Inertia::render($view, [
            'training_result' => $trainingResult,
            'incorrect_questions' => $incorrectQuestions,
        ]);
    }
}
