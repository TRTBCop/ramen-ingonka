<?php

namespace Database\Factories;

use App\Enums\QuestionLevelEnum;
use App\Models\Curriculum;
use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class QuestionFactory extends Factory
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
            'level' => fake()->numberBetween(1, 5), // 난이도
            'question' => fake()->text(), // 풀이과정
            'inquiry' => fake()->text(), // 문제
            'options' => fake()->text(), // 보기
            'answers' => [
                [
                    'type' => 1,
                    'action' => 1,
                    'answer' => [1, 2],
                    'choice_symbol' => true,
                    'choices' => [
                        fake()->sentence(),
                        fake()->sentence(),
                        fake()->sentence(),
                        fake()->sentence(),
                    ]
                ]
            ], // 답안
            'explanation' => fake()->text(),
            'published_at' => fake()->dateTime()->format('Y-m-d H:i:s'),

        ];
    }

    public function configure(): QuestionFactory
    {
        return $this->afterCreating(function (Question $question) {
            $question->syncTagsWithType([fake()->word(), fake()->word()], 'questions');
        });
    }
}
