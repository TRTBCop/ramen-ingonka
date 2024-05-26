<?php

namespace Database\Factories;

use App\Models\Curriculum;
use App\Models\Student;
use App\Models\Training;
use App\Models\TrainingResult;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TrainingResult>
 */
class TrainingResultFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $min = 0; // 최소 문제 개수
        $totalQuestions = fake()->numberBetween($min, 20);
        $totalCorrect = rand($min, $totalQuestions);

        return [
            'curriculum_id' => Curriculum::factory()->create()->id,
            'student_id' => Student::factory()->create()->id,
            'training_id' => Training::factory()->create()->id,
            'stage' => fake()->numberBetween(1, 3), // 1~3단계
            'point' => fake()->numberBetween(40, 100),
            'total_questions' => $totalQuestions,
            'total_correct' => $totalCorrect,
            'completed_at' => $totalQuestions ? fake()->dateTimeBetween('-100 day') : null,
        ];
    }

    public function configure(): TrainingResultFactory|Factory
    {
        return $this->afterCreating(function (TrainingResult $trainingResult) {
            if (!$trainingResult->student->curricula()->wherePivot('curriculum_id', $trainingResult->curriculum_id)->exists()) {
                $trainingResult->student->curricula()->attach($trainingResult->curriculum_id);
            }
        });
    }
}
