<?php

namespace App\Services;

use App\Enums\TrainingConceptTextType;
use App\Models\Curriculum;
use App\Models\Question;
use App\Models\Student;
use App\Models\Training;
use App\Models\TrainingConceptText;

class TrainingService
{
    public function __construct()
    {
    }

    /**
     * 학습에 전달할 pageData 기본 값
     */
    public static function getStagePageData(Training $training, int|string $step)
    {
        $isPreview = preg_match('/preview/', request()->route()->getName());
        $training->load(['curriculum.ancestors', 'curriculum.trainings']);

        $stage = $training->stage;

        $typePattern = ($step == 'operations') ? 'operations' : "trainings.stage_$stage.$step";

        $questions = array_values($training->questions()->withPivot('extra')->get()?->filter(function (Question $question) use ($typePattern) {
            return isset($question->pivot->extra['type'])
            && $typePattern  === $question->pivot->extra['type'];
        })->toArray());

        foreach ($questions as $i => $question) {
            $questions[$i] = QuestionService::questionToAppQuestion($question);
        }

        $contents = $step === 'operations' ? $training->contents['basic_operations'] : $training->contents[$step];

        $data = [
            'training' => $training,
            'contents' => $contents,
            'questions' => $questions,
            'step' => $step,
            'is_preview' => $isPreview,
        ];

        if (!$isPreview) {
            // 마지막 훈련이 완료이고 복습 가능하면 복습 데이터 생성
            $trainingResult = TrainingResultsService::getLatestTrainingResult($training);
            if (isset($trainingResult->completed_at)) {
                if (self::isRestudyAvailable($training)) {
                    $trainingResult = TrainingResultsService::createTrainingResult($training);
                } else {
                    return to_route('app.main')->with('message', ['error', '복습 횟수 초과']);
                }
            }

            $stepResult = $trainingResult->stepByKey($step);

            $data['training_result'] = $trainingResult;
            $data['step_result'] = $stepResult;
            $data['timer'] =  $trainingResult->timer;
        }

        return $data;
    }

    /**
     * 개념훈련 - 개념학습 에 전달할 pageData 기본 값
     */
    public static function getStage1TextsPageData(Training $training, TrainingConceptText $trainingConceptText, string $type, array $contents, bool $isPreview)
    {
        $step = 'texts';
        $training->load(['curriculum.ancestors', 'curriculum.trainings']);
        // 지문 번호모음
        $trainingConceptTextIds = $training->training_concept_texts()->get()->pluck('id')->toArray();

        $questions = array_values($training->questions()->withPivot('extra')->get()?->filter(function (Question $question) use ($trainingConceptText) {
            return isset($question->pivot->extra['type'])
            && isset($question->pivot->extra['model'])
            && $question->pivot->extra['model_id'] == $trainingConceptText->id;
        })->toArray());

        $mathmlService = new MathmlService();
        $contents = $mathmlService->setMathmlToImage($contents);

        foreach ($questions as $i => $question) {
            // 개념 정리는 정답을 전부 보내 줌.
            if ($type === TrainingConceptTextType::DONE) {
                continue;
            }

            // 끊어 읽기는 점수 산정이 없기 때문에 정답 보냄
            if ('training_concept_texts.readings' === $question['pivot']['extra']['type']) {
                $questions[$i] = QuestionService::questionToMathmlToImage($question);
                continue;
            }
            $questions[$i] = QuestionService::questionToAppQuestion($question);
        }

        $data = [
            'contents' => $contents,
            'questions' => $questions,
            'training' => $training,
            'training_concept_text_id' => $trainingConceptText->id,
            'training_concept_text_ids' => $trainingConceptTextIds,
            'training_concept_text_type' => $type,
            'step' => $step,
            'is_preview' => $isPreview,
        ];

        if (!$isPreview) {
            $trainingResult = TrainingResultsService::getLatestTrainingResult($training);

            $data['training_result'] = $trainingResult;
            $data['timer'] = $trainingResult->timer;

            $trainingConceptTextResult = $trainingResult->stepByKey('texts')->trainingConceptTexts->where('training_concept_text_id', $trainingConceptText->id)->first();
            if ($type === TrainingConceptTextType::SUMMARIZATIONS || $type === TrainingConceptTextType::REINFORCEMENTS) {
                $trainingConceptTextResult->is_reading_completed = true;
                $trainingConceptTextResult->save();
                $stepResult = $trainingConceptTextResult->$type;
                $stepResult->load(['questions']);
                $data['step_result'] = $stepResult;
            } else {
                $data['step_result'] = $trainingConceptTextResult->step;
            }
        }

        return $data;
    }

    /**
     * 복습 가능 여부
     * 무료체험이거나 복습 2회 진행시 복습 불가
     */
    public static function isRestudyAvailable(Training $training)
    {
        /** @var Student $user */
        $user = auth()->user();

        $maxRestudyCount = 3;

        return $training->results()->where(['student_id' => $user->id])->count() < $maxRestudyCount && !$user->isFree();
    }

    /**
     * 학습 결과 api의 기본 응답 값 세팅
     *
     */
    public static function getTrainingSubmitResponse(Question $question, string|array $userAnswers, int $answerRowIndex = null, int $answerColIndex = null): array
    {
        $response = [];
        // response 세팅
        if (isset($answerRowIndex)) {
            $isOderingType = $question->answers[$answerRowIndex]['type'] === 3;

            $isCorrect = QuestionService::verifyQuestionAnswer($question, $userAnswers, $answerRowIndex, $answerColIndex);

            if ($isOderingType) {
                // 순서 맞추기인 경우 정답이 아닌 빈칸은 뺌.
                $correctAnswers = QuestionService::getFilterCorrectAnswers($question, $userAnswers, $answerRowIndex);
            } else {
                // 정답을 맞췄거나 개념 요약하기인 경우는 정답 값을 반환.
                $correctAnswers = QuestionService::getCorrectAnswers($question, $answerRowIndex, $answerColIndex);
            }


            $response = [
                'is_correct' => $isCorrect,
            ];

            if (isset($correctAnswers)) {
                $response['correct_answers'] = $correctAnswers;
            }
        } else {
            $response = [
                'question' => $question,
            ];
        }

        return $response;
    }

    /**
     * 무료 체험 학생이 진행 가능한 학습인지 체크하는 메서드
     */
    public static function gaurdFreeCurriculum(Curriculum $curriculum)
    {
        /** @var Student $user */
        $user = auth()->user();

        if (!$user->isFree()) {
            return true;
        }

        $curricula = Curriculum::defaultOrder()
        ->descendantsOf(Curriculum::MATH_ROOT_ID)->toTree();

        $parentCurriculumId = CurriculumService::getRootCategoryId($user->grade, $user->term);

        $curriculumByGradeAndTerm = $curricula->find($parentCurriculumId)->children->toArray();

        $firstCurriculum = null;

        $middleCurriculum = $curriculumByGradeAndTerm[0]['children'][0];

        if (!empty($middleCurriculum['children'])) {
            $firstCurriculum = $middleCurriculum['children'][0];
        } else {
            $firstCurriculum = $middleCurriculum;
        }

        if ($curriculum->id == $firstCurriculum['id']) {
            return true;
        }

        return false;
    }
}
