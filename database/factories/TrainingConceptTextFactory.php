<?php

namespace Database\Factories;

use App\Models\Curriculum;
use App\Models\Question;
use App\Models\Training;
use App\Models\TrainingConceptText;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Training>
 */
class TrainingConceptTextFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'training_id' => Training::factory(),
            'readings' => [],
            'summarizations' => [],
            'reinforcements' => []
        ];
    }

    // 개념읽기
    public function readings(): Factory|TrainingConceptTextFactory
    {
        $text = '안녕하세요//밤바람이 차네요//반갑습니다';
        $arrText = explode('//', $text);
        $question = Question::factory()->create();

        return $this->state([
            'readings' => [
                [
                    'text' => $arrText[0],
                    'type' => 0
                ],
                [
                    'text' => $arrText[1],
                    'type' => 1,
                    'image' => [
                        'src' => fake()->image(),
                        'last' => true
                    ]
                ],
                [
                    'text' => $arrText[2],
                    'type' => 2,
                    'question' => [
                        'id' => $question->id,
                    ]
                ]
            ],
        ]);
    }

    public function summarizations(): Factory|TrainingConceptTextFactory
    {
        return $this->state([
            'summarizations' => [
                'questions' => [
                    [
                        'id' => Question::factory()->create()->id
                    ]
                ]
            ],
        ]);
    }

    public function reinforcements(): Factory|TrainingConceptTextFactory
    {
        return $this->state([
            'reinforcements' => [
                [
                    'text' => fake()->text(),
                    'inquiry' => fake()->text(),
                    'answers' => [
                        [
                            'type' => 1,
                            'choice_symbol' => false,
                            'answer' => [
                                1
                            ],
                        ]
                    ]
                ],
                [
                    'text' => fake()->text(),
                    'inquiry' => fake()->text(),
                    'answers' => [
                        [
                            'type' => 2,
                            'choice_symbol' => false,
                            'answer' => [
                                1
                            ],
                            'choices' => [
                                '선지1'
                            ]
                        ]
                    ]
                ]
            ],
        ]);
    }

    public function configure(): TrainingConceptTextFactory
    {
        return $this->afterCreating(function (TrainingConceptText $trainingConceptText) {
            $training = $trainingConceptText->training;
            $sync = [];

            // 개념읽기
            foreach ($trainingConceptText->readings as $item) {
                if ($item['type'] == 2 && isset($item['question']['id'])) {
                    $sync[$item['question']['id']] = [
                        'extra' => [
                            'type' => 'training_concept_texts.readings',
                            'model' => TrainingConceptText::class,
                            'model_id' => $trainingConceptText->id
                        ]
                    ];
                }
            }

            // 개념요약
            $summarizationsQuestions = $trainingConceptText->summarizations['questions'];
            foreach ($summarizationsQuestions as $item) {
                if (!empty($item['id'])) {
                    $sync[$item['id']] = [
                        'extra' => [
                            'type' => 'training_concept_texts.readings',
                            'model' => TrainingConceptText::class,
                            'model_id' => $trainingConceptText->id
                        ]
                    ];
                }
            }

            if ($sync) {
                $training->questions()->sync($sync, false);
            }
        });
    }
}
