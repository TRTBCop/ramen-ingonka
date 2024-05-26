<?php

namespace App\Http\Controllers\App;

use App\Enums\TrainingConceptTextType;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Auth\TrainingSubmitRequest;
use App\Models\Question;
use App\Models\StepResult;
use App\Models\Training;
use App\Models\TrainingConceptText;
use App\Models\TrainingResult;
use App\Services\QuestionService;
use App\Services\StepResultService;
use App\Services\TrainingService;
use App\Services\TrainingResultsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class TrainingController extends BaseController
{
    public function __construct()
    {
    }

    /**
     * 훈련 - 메인 화면
     * GET|HEAD | app/trainings/{training} | app.training.show
     */
    public function show(Training $training): Response|RedirectResponse
    {
        /**
         * @var \App\Models\Student
         */
        $student = auth()->user();

        $trainingResult = TrainingResultsService::getLatestTrainingResult($training);


        if (!TrainingService::gaurdFreeCurriculum($training->curriculum)) {
            return to_route('app.main')->with('message', ['error', __('messages.app.trainings.free_only_first_study_guard')]);
        };

        // 마지막 훈련이 완료이고 복습 가능하면 복습 데이터 생성
        $trainingResult = TrainingResultsService::getLatestTrainingResult($training);
        if (isset($trainingResult->completed_at) && TrainingService::isRestudyAvailable($training)) {
            $trainingResult = TrainingResultsService::createTrainingResult($training);
        }

        $training->curriculum->load(['trainings.results' => function ($query) use ($student) {
            $query->where('student_id', $student->id)->whereNotNull('completed_at');
        }, 'ancestors.children.children' => function ($query) use ($student) {
            $query->with(['trainings.results' => function ($query) use ($student) {
                $query->where('student_id', $student->id)->whereNotNull('completed_at');
            }, 'children.trainings.results' => function ($query) use ($student) {
                $query->where('student_id', $student->id)->whereNotNull('completed_at');
            }])->hasParent();
        }]);

        $training->load(['results' => function ($query) use ($student) {
            $query->where('student_id', $student->id)->with('steps');
        }]);

        return Inertia::render('trainings/Show', [
            'config' => [
                'dbcode' => [
                    'curricula' => dbcode('curricula'),
                    'students' => dbcode('students'),
                ],
            ],
            'curriculum' => $training->curriculum,
            'training' => $training,
            'stage' => $training->stage
        ]);
    }


    /**
     * 개념 훈련 - 개념 학습
     * GET|HEAD | app/trainings/{training}/1/texts | app.trainings.stage1.texts.show
     */
    public function showStage1Texts(Training $training): Response|RedirectResponse
    {
        $trainingResult = TrainingResultsService::getLatestTrainingResult($training);

        $stepResultByTexts = $trainingResult->stepByKey('texts');
        $trainingConceptTextResult = $stepResultByTexts->trainingConceptTexts->whereNull('completed_at')->first();

        if ($stepResultByTexts->completed_at || !$trainingConceptTextResult) {
            to_route('app.main')->with('message', ['error', '모든 지문을 학습 했습니다.']);
        }

        $step = '';
        if (!$trainingConceptTextResult->is_reading_completed) {
            $step = TrainingConceptTextType::READINGS;
        } elseif (!$trainingConceptTextResult->summarizations->completed_at) {
            $step = TrainingConceptTextType::SUMMARIZATIONS;
        } elseif (!$trainingConceptTextResult->reinforcements->completed_at) {
            $step = TrainingConceptTextType::REINFORCEMENTS;
        }



        $trainingMethod = 'showStage1Texts'.ucfirst($step);

        if (method_exists($this, $trainingMethod)) {
            return call_user_func([$this, $trainingMethod], $training, $trainingConceptTextResult->trainingConceptText);
        }

        abort(404);
    }

    /**
     * 개념 훈련 - 개념 학습 - 개념읽기
     * GET|HEAD | app/trainings/{training}/1/texts/{trainingConceptText}/readings | app.trainings.stage1.texts.readings.show
     * GET|HEAD | app/trainings/{training}/preview//1/texts/{trainingConceptText}/readings | app.trainings.stage1.texts.readings.preview.show
     */
    public function showStage1TextsReadings(Training $training, TrainingConceptText $trainingConceptText): Response|RedirectResponse
    {
        $isPreview = preg_match('/preview/', request()->route()->getName());
        $view = 'trainings/stage1/ShowTextReading';

        $pageData = TrainingService::getStage1TextsPageData($training, $trainingConceptText, TrainingConceptTextType::READINGS, $trainingConceptText->readings, $isPreview);

        return Inertia::render($view, $pageData);
    }

    /**
     * 개념 훈련 - 개념 학습 - 개념요약
     * GET|HEAD | app/trainings/{training}/1/texts/{trainingConceptText}/summarizations | app.trainings.stage1.texts.summarizations.show
     * GET|HEAD | app/trainings/preview/{training}/1/texts/{trainingConceptText}/summarizations/{question?} | app.trainings.stage1.texts.summarizations.preview.show
     */
    public function showStage1TextsSummarizations(Training $training, TrainingConceptText $trainingConceptText, Question $question = null): Response|RedirectResponse
    {
        $isPreview = preg_match('/preview/', request()->route()->getName());
        $view = 'trainings/stage1/ShowTextSummarization';

        $contents = [
            TrainingConceptTextType::READINGS => $trainingConceptText->readings,
            TrainingConceptTextType::SUMMARIZATIONS => $trainingConceptText->summarizations,
        ];


        $pageData = TrainingService::getStage1TextsPageData($training, $trainingConceptText, TrainingConceptTextType::SUMMARIZATIONS, $contents, $isPreview);

        return Inertia::render($view, $pageData);
    }

    /**
     * 개념 훈련 - 개념 학습 - 개념다지기
     * GET|HEAD | app/trainings/{training}/1/texts/{trainingConceptText}/reinforcements | app.trainings.stage1.texts.reinforcements.show
     * GET|HEAD | app/trainings/preview/{training}/1/texts/{trainingConceptText}/reinforcements/{question?} | app.trainings.stage1.texts.reinforcements.preview.show
     */
    public function showStage1TextsReinforcements(Training $training, TrainingConceptText $trainingConceptText, Question $question = null): Response|RedirectResponse
    {
        $isPreview = preg_match('/preview/', request()->route()->getName());
        $view = 'trainings/stage1/ShowTextReinforcement';

        $pageData = TrainingService::getStage1TextsPageData($training, $trainingConceptText, TrainingConceptTextType::REINFORCEMENTS, $trainingConceptText->reinforcements, $isPreview);

        return Inertia::render($view, $pageData);
    }


    /**
     * 개념 훈련 - 개념 학습 - 개념정리
     * GET|HEAD | app/trainings/{training}/1/texts/{trainingConceptText}/review| app.trainings.stage1.texts.review.show
     */
    public function showStage1TextsReview(Training $training, TrainingConceptText $trainingConceptText): Response|RedirectResponse
    {
        $view = 'trainings/stage1/ShowTextReview';

        $contents = [
            TrainingConceptTextType::READINGS => $trainingConceptText->readings,
            TrainingConceptTextType::SUMMARIZATIONS => $trainingConceptText->summarizations,
        ];

        $pageData = TrainingService::getStage1TextsPageData($training, $trainingConceptText, TrainingConceptTextType::DONE, $contents, false);

        return Inertia::render($view, $pageData);
    }

    /**
     * 개념 훈련 - 기초 연산
     * GET|HEAD | app/trainings/{training}/1/operations| app.trainings.stage1.operations.show
     * GET|HEAD | app/trainings/preview/{training}/1/operations/{question?}| app.trainings.stage1.operations.preview.show
     */
    public function showStage1Operations(Training $training): Response|RedirectResponse
    {
        $view = 'trainings/stage1/ShowOperations';

        $trainingResult = TrainingResultsService::getLatestTrainingResult($training);
        if (isset($trainingResult->completed_at)) {
            if (TrainingService::isRestudyAvailable($training)) {
                $trainingResult = TrainingResultsService::createTrainingResult($training);
            } else {
                return to_route('app.main')->with('message', ['error', '복습 횟수 초과']);
            }
        }

        $pageData = TrainingService::getStagePageData($training, 'operations');

        return Inertia::render($view, $pageData);
    }

    /**
     * 유형 훈련
     * GET|HEAD | app/trainings/{training}/2/{step}| app.trainings.stage2.show
     * GET|HEAD | app/trainings/preview/{training}/2/{step}/{question?}| app.trainings.stage2.preview.show
     */
    public function showStage2(Training $training, int $step): Response|RedirectResponse
    {
        $view = 'trainings/stage2/Show';

        // 훈련 단계는 4단계까지 있음
        if ($step < 0 || $step > 3) {
            abort(404);
        }

        $pageData = TrainingService::getStagePageData($training, $step);

        return Inertia::render($view, $pageData);
    }

    /**
     * 서술형 훈련
     * GET|HEAD | app/trainings/{training}/3/{step}| app.trainings.stage3.show
     * GET|HEAD | app/trainings/preview/{training}/3/{step}/{question?}| app.trainings.stage3.preview.show
     */
    public function showStage3(Training $training, int $step): Response|RedirectResponse
    {
        $view = 'trainings/stage3/Show';

        // 훈련 단계는 3단계까지 있음
        if ($step < 0 || $step > 2) {
            abort(404);
        }

        $pageData = TrainingService::getStagePageData($training, $step);

        return Inertia::render($view, $pageData);
    }

    /**
     * 훈련 완료 상세
     * GET|HEAD | app/training/{training}/results/{trainingResult} | app.training.results.show
     */
    public function showResult(Training $training, TrainingResult $trainingResult): Response|RedirectResponse
    {
        $view = 'trainings/results/Show';

        $training->load(['curriculum.ancestors']);
        $trainingResult->load(['steps.questions']);

        if (!isset($trainingResult->completed_at)) {
            return to_route('app.main')->with('message', ['error', __('messages.app.students.not_completed_result')]);
        }

        $pageData = [
            'training' => $training,
            'training_result' => $trainingResult,
        ];

        return Inertia::render($view, $pageData);
    }

    /**
     * 훈련 완료 인트로 화면
     * GET|HEAD | app/training/{training}/results/{trainingResult}/summary | app.training.results.summary.show
     */
    public function showResultSummary(Training $training, TrainingResult $trainingResult): Response|RedirectResponse
    {
        $view = 'trainings/results/summary/Show';

        $training->load(['curriculum.ancestors']);

        if (!isset($trainingResult->completed_at)) {
            return to_route('app.main')->with('message', ['error', __('messages.app.students.not_completed_result')]);
        }

        $pageData = [
            'training' => $training,
            'training_result' => $trainingResult,
        ];

        return Inertia::render($view, $pageData);
    }

    /**
     * 학습 완료
     * GET|HEAD | app/trainings/{training}/results/{trainingResult}/steps/{stepResult} | app.trainings.results.steps.show
     */
    public function showStepResult(Training $training, TrainingResult $trainingResult, StepResult $stepResult): Response|RedirectResponse
    {
        $view = 'trainings/results/steps/Show';

        $training->load(['curriculum.ancestors']);
        $trainingResult->load(['steps']);
        $stepResult->load(['questions', 'trainingConceptTexts']);

        if (!isset($stepResult->completed_at)) {
            return to_route('app.main')->with('message', ['error', __('messages.app.students.not_completed_result')]);
        }

        $pageData = [
            'training' => $training,
            'training_result' => $trainingResult,
            'step_result' => $stepResult,
        ];

        return Inertia::render($view, $pageData);
    }

    /**
     * 오답 해설 (개념훈련 제외)
     * GET|HEAD | app/trainings/{training}/results/{trainingResult}/steps/{stepResult}/explanation | app.trainings.results.steps.explanation.show
     */
    public function showStepResultExplanation(Training $training, TrainingResult $trainingResult, StepResult $stepResult): Response|RedirectResponse
    {
        $view = 'trainings/results/steps/explanation/Show';

        $training->load('curriculum.ancestors');
        $stage = $training->stage;

        if ($stage === 1) {
            return to_route('app.main')->with('message', ['error', '개념훈련은 오답해설이 없습니다.']);
        }

        $typePattern = "trainings.stage_$stage.$stepResult->key";

        $questions = array_values($training->questions()->withPivot('extra')->get()?->filter(function (Question $question) use ($typePattern) {
            return isset($question->pivot->extra['type'])
            && $typePattern  === $question->pivot->extra['type'];
        })->toArray());

        foreach ($questions as $i => $question) {
            $questions[$i] = QuestionService::questionToAppQuestion($question);
        }

        $contents = $stepResult->key === 'operations' ? $training->contents['basic_operations'] : $training->contents[$stepResult->key];

        $pageData = [
            'contents' => $contents,
            'questions' => $questions,
            'step' => $stepResult->key,
            'training' => $training,
            'training_result' => $trainingResult,
            'step_result' => $stepResult,
        ];

        return Inertia::render($view, $pageData);
    }

    /**
     * 공통 훈련 - 결과 제출
     * POST | app/trainings/{training}/submit | app.trainings.submit
     * @return JsonResponse
     */
    public function submit(TrainingSubmitRequest $request, Training $training): JsonResponse
    {
        $input = $request->all();
        $question = Question::find($input['question_id']);
        if (isset($input['step_result_id'])) {
            $stepResult = StepResult::find($input['step_result_id']);
        }
        $userAnswers = $input['answers'];
        $answerRowIndex = $input['answer_row_index'] ?? null;
        $answerColIndex = $input['answer_col_index'] ?? null;
        $timer = $input['timer'];

        $response = TrainingService::getTrainingSubmitResponse($question, $userAnswers, $answerRowIndex, $answerColIndex);

        if (isset($stepResult)) {
            $questionResult = StepResultService::getQuestionResult($stepResult, $question);
            if (isset($answerRowIndex)) {
                $questionResult->setAnswerAt($userAnswers, $answerRowIndex, $answerColIndex);
            } else {
                $questionResult->setAnswers($userAnswers);
            }
            $stepResult->refresh();

            /**
             * @var TrainingResult
             */
            $trainingResult = $stepResult->model;
            $trainingResult->timer = $timer;
            $trainingResult->save();

            $response['step_result'] = $stepResult;
        }


        return $this->sendResponse($response, '성공');
    }
}
