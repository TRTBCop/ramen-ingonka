<?php

namespace Database\Factories;

use App\Models\Curriculum;
use App\Models\Question;
use App\Models\Training;
use App\Models\TrainingConceptText;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Training>
 */
class TrainingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'curriculum_id' => Curriculum::factory(),
            'stage' => fake()->randomElement([1, 2, 3]),
            'contents' => []
        ];
    }

    public function training1(): Factory|TrainingFactory
    {

        $questions = [
            [
                'id' => Question::factory()->create()->id,
                'type' => 1,
            ],
            [
                'id' => Question::factory()->create()->id,
                'type' => 2,
            ],
        ];

        // 기초연산
        return $this->state([
            'stage' => 1,
            'contents' => [
                'basic_operations' => [
                    'questions' => $questions
                ],
            ],
        ]);
    }

    public function training2(): Factory|TrainingFactory
    {
        return $this->state([
            'stage' => 2,
            'contents' => [
                [
                    Question::factory()->create()->id,
                    Question::factory()->create()->id,
                ],
                [
                    Question::factory()->create()->id,
                    Question::factory()->create()->id,
                ],
                [],
                [],
            ],
        ]);
    }

    public function training3(): Factory|TrainingFactory
    {
        return $this->state([
            'stage' => 3,
            'contents' => [
                [
                    Question::factory()->create()->id,
                    Question::factory()->create()->id,
                ],
                [
                    Question::factory()->create()->id,
                    Question::factory()->create()->id,
                ],
                [],
            ],
        ]);
    }

    public function configure(): TrainingFactory
    {
        return $this->afterCreating(function (Training $training) {
            if ($training->stage == 1) {
                TrainingConceptText::factory()->state([
                    'training_id' => $training->id,
                ])->readings()->summarizations()->reinforcements()->create();
            }

            if ($training->stage == 1 && isset($training->contents['basic_operations']['questions'])) {
                $training->questions()->sync(array_column($training->contents['basic_operations']['questions'], 'id'), false);
            }

            if (in_array($training->stage, [2, 3]) && $training->contents) {
                $training->questions()->sync(Arr::flatten($training->contents), false);
            }
        });
    }
}
