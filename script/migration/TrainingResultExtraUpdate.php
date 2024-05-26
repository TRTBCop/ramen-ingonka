<?php

namespace Script\migration;

use App\Models\QuestionResult;
use App\Models\StepResult;
use App\Models\Training;
use App\Models\TrainingConceptTextResult;
use App\Models\TrainingResult;
use App\Services\TrainingResultsService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 *  php artisan script migration/TrainingResultExtraUpdate;
 *  trainingResult에 extra를 db로 분리 시켰기 때문에 해당 정보를 db로 만듦
 *
 */
class TrainingResultExtraUpdate extends Seeder
{
    public function run(): void
    {
        activity()->disableLogging();

        Schema::disableForeignKeyConstraints();
        DB::disableQueryLog();

        $this->index();
        Schema::enableForeignKeyConstraints();
    }

    public function index(): void
    {
        $start = now();

        $trainingResults = TrainingResult::all();

        $this->command->getOutput()->progressStart($trainingResults->count());

        foreach ($trainingResults as $trainingResult) {
            if ($trainingResult->completed_at) {
                $extra = $trainingResult->extra;

                $stepInfo = $extra['step_info'];

                foreach ($stepInfo as $key => $step) {
                    $stepResult = $this->createStepResult($trainingResult->student_id, $trainingResult, $key);

                    if ($key === 'texts') {
                        foreach ($step['text_results'] as $textResult) {
                            $trainingConceptTextResult = TrainingConceptTextResult::create([
                                'student_id' => $trainingResult->student_id,
                                'step_result_id' => $stepResult->id,
                                'training_concept_text_id' => $textResult['id'],
                                'is_reading_completed' => true,
                                'completed_at' => $trainingResult->completed_at,
                            ]);

                            $types = ['summarizations', 'reinforcements'];

                            foreach ($types as $type) {
                                $stepResultByType = $this->createStepResult($trainingResult->student_id, $trainingConceptTextResult, $type);

                                $questions = $textResult[$type]['questions'];

                                foreach ($questions as $question) {
                                    $questionResult = $this->createQuestionResult($stepResultByType, $trainingResult->student_id, $question);
                                    $questionResult->evaluateCompletion();
                                }
                            }
                        }
                    } else {
                        $questions = $step['questions'];

                        foreach ($questions as $question) {
                            $questionResult = $this->createQuestionResult($stepResult, $trainingResult->student_id, $question);
                            $questionResult->evaluateCompletion();
                        }
                    }
                }
            } else {
                $trainingResult->delete();
            }

            $this->command->getOutput()->progressAdvance();
        }

        $this->command->getOutput()->progressFinish();
        $this->done($start);
    }


    public function createStepResult($studentId, TrainingResult|TrainingConceptTextResult $parentResult, string|int $key)
    {
        return StepResult::create([
            'student_id' => $studentId,
            'model_id' => $parentResult->id,
            'model_type' => get_class($parentResult),
            'completed_at' => $parentResult->completed_at,
            'key' => $key,
        ]);
    }

    public function createQuestionResult(StepResult $stepResult, $studentId, array $question)
    {
        $answers = $question['answers'];

        foreach ($answers as $i => $answer) {
            $answers[$i] = $this->arrayReplaceKey($answer, 'userAnswers', 'userAnswer');
            $answers[$i] = $this->arrayReplaceKey($answers[$i], 'correctAnswers', 'correctAnswer');
        }

        return QuestionResult::create([
            'student_id' => $studentId,
            'question_id' => $question['id'],
            'model_type' => get_class($stepResult),
            'model_id' => $stepResult->id,
            'answers' => $answers,
            'completed_at' => $stepResult->completed_at,
        ]);
    }

    public function arrayReplaceKey($array, $oldKey, $newKey)
    {
        if (!array_key_exists($oldKey, $array)) {
            return $array;
        }
        $keys = array_keys($array);
        $keys[array_search($oldKey, $keys)] = $newKey;
        return array_combine($keys, $array);
    }


    public function done($start): void
    {
        $this->command->info('Done ('.$start->diffInSeconds(now()).' seconds)'.PHP_EOL);
    }
}
