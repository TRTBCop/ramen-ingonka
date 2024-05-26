<?php

namespace App\Services;

use App\Models\Question;
use App\Models\QuestionResult;
use App\Models\StepResult;

class StepResultService
{
    private StepResult $stepResult;

    public function __construct()
    {
    }

    /**
     * stepResult에 연관된 questionResult를 생성
     */
    public static function createQuestionResult(StepResult $stepResult, Question $question)
    {
        return QuestionResult::create([
            'student_id' => auth()->user()->id,
            'question_id' => $question->id,
            'model_type' => get_class($stepResult),
            'model_id' => $stepResult->id,
            'answers' => []
        ]);
    }

    /**
     * question을 받아 stepResult에 해당 question의 questionResult를 찾은 후 반환
     * 해당 questionResult가 없을 경우 생성 후 반환
     */
    public static function getQuestionResult(StepResult $stepResult, Question $question): QuestionResult
    {
        $questionResult = $stepResult->questions->where('question_id', $question->id)->first();

        if (!$questionResult) {
            $questionResult =  self::createQuestionResult($stepResult, $question);
        }

        return $questionResult;
    }
}
