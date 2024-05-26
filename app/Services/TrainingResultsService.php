<?php

namespace App\Services;

use App\Models\Question;
use App\Models\StepResult;
use App\Models\Training;
use App\Models\TrainingConceptText;
use App\Models\TrainingConceptTextResult;
use App\Models\TrainingResult;

class TrainingResultsService
{
    public function __construct()
    {
    }

    public static function createTrainingResult(Training $training): TrainingResult
    {
        $trainingResult = TrainingResult::create([
            'student_id' => auth()->user()->id,
            'curriculum_id' => $training->curriculum_id,
            'training_id' => $training->id,
            'round' => $training->results()->where(['student_id' => auth()->id()])->count(),
        ]);

        $stepKeys = [];

        switch($training->stage) {
            case 1:
                $stepKeys = ['texts', 'operations'];
                break;
            case 2:
                $stepKeys = [0, 1, 2, 3];
                break;
            case 3:
                $stepKeys = [0, 1, 2];
                break;
        }

        foreach ($stepKeys as $step) {
            $stepResult = self::createStepResult($trainingResult, $step);

            if ($step === 'texts') {
                $trainingConceptTexts = $training->training_concept_texts;

                foreach ($trainingConceptTexts as $trainingConceptText) {
                    $trainingConceptTextResult = self::createTrainingConceptTextResult($stepResult, $trainingConceptText);

                    $types = ['summarizations', 'reinforcements'];

                    foreach ($types as $type) {
                        $stepResultByType = self::createStepResult($trainingConceptTextResult, $type);

                        foreach ($trainingConceptText->$type['questions'] as $question) {
                            $question =  Question::find($question['id']);
                            StepResultService::createQuestionResult($stepResultByType, $question);
                        }
                    }
                }
            } else {
                $content = $trainingResult->training->contents[$step === 'operations' ? 'basic_operations' : $step];

                foreach ($content['questions'] as $question) {
                    $question =  Question::find($question['id']);
                    StepResultService::createQuestionResult($stepResult, $question);
                }
            }
        }

        $trainingResult->save();

        return $trainingResult;
    }

    public static function getLatestTrainingResult(Training $training): TrainingResult
    {
        $trainingResult = $training->results()->where('student_id', auth()->user()->id)->latest()->first();

        if (!$trainingResult) {
            $trainingResult = self::createTrainingResult($training);
        }

        return $trainingResult;
    }

    public static function createTrainingConceptTextResult(StepResult $stepResult, TrainingConceptText $trainingConceptText)
    {
        return TrainingConceptTextResult::create([
            'student_id' => auth()->user()->id,
            'step_result_id' => $stepResult->id,
            'training_concept_text_id' => $trainingConceptText->id,
        ]);
    }

    public static function createStepResult(TrainingResult|TrainingConceptTextResult $parentResult, string|int $key)
    {
        return StepResult::create([
            'student_id' => auth()->user()->id,
            'model_id' => $parentResult->id,
            'model_type' => get_class($parentResult),
            'key' => $key,
        ]);
    }
}
