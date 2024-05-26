<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\Test;
use App\Models\TestResult;
use App\Services\CurriculumService;
use App\Services\TestService;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TestResult>
 */
class TestResultFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => Str::uuid(),
            'student_id' => Student::factory(),
            'test_id' => Test::factory(),
            'point' => fake()->numberBetween(40, 100),
            'extra' => [],
            'completed_at' => fake()->dateTimeBetween('-100 day')
        ];
    }

    public function configure(): TestResultFactory
    {
        return $this->afterCreating(function (TestResult $testResult) {
            if (!$testResult->extra) {
                $testService = new TestService(new CurriculumService());
                $test = Test::with('questions')->findOrFail($testResult->test_id);

                $extra = [
                    'point' => 0,
                    'questions' => []
                ];


                $totalCorrectCount = 0;
                $totalQuestionCount = 0;
                foreach ($test->questions as $question) {
                    if ($testResult->point == 100 && $question->is_extend) {
                        continue;
                    }

                    $correctCount = 0;
                    $questionCount = count($question->contents['answers']);
                    //$totalQuestionCount += $questionCount;

                    $questionScore = 100 / ($totalQuestionCount + $questionCount);
                    $requiredCorrectAnswers = $totalCorrectCount + ceil($testResult->point / $questionScore);

                    $arrExtraAnswer = [];
                    foreach ($question->contents['answers'] as $answerKey => $contentAnswer) {
                        // 정답체크
                        $correct = $requiredCorrectAnswers >= $totalCorrectCount + $answerKey + 1;
                        $arrExtraAnswer[] = [
                            'correct' => $correct,
                            'action' => $contentAnswer['action']
                        ];
                        $correctCount += $correct;
                    }

                    $totalCorrectCount += $correctCount;

                    $extra['questions'][$question->id] = [
                            'id' => $question->id,
                            'is_extend' => $question->is_extend,
                            'element' => $question->curriculum->element->value,
                            'question_count' => $questionCount, // 해설문제수
                            'correct_count' => $correctCount, // 정답수
                            'answers' => $arrExtraAnswer, // 결과
                            'completed_at' => now(),
                        ] + (!$question->is_extend ? ['meta_cognition' => fake()->numberBetween(0, 2)] : []);
                }

                $point['point'] = $testService->getPoint($extra);
                $extra += $point;

                // 리포트 생성
                $extra['report'] = $testService->setReport($test, $extra, [
                    'name' => 'test',
                    'test_title' => $test->title
                ]);

                $testResult->point = $extra['point'];
                $testResult->extra = $extra;
                $testResult->save();
            }
        });
    }
}
