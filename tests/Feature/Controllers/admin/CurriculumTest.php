<?php

namespace Admin;

use App\Models\Admin;
use App\Models\Curriculum;
use App\Models\Question;
use App\Models\Training;
use App\Models\TrainingConceptText;
use Database\Seeders\CurriculumSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class)->beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
    $this->seed(CurriculumSeeder::class);
    $this->admin = Admin::factory()->create()->assignRole('super');
});


test('admin.curricula.nested-set 계층목록', function () {
    $this->actingAs($this->admin, 'admin')->get(route('admin.curricula.nested-set'))
        ->assertInertia(
            fn (Assert $page) => $page
            ->component('curricula/NestedSet')
            ->has('page')
        );
})->group('admin', 'curricula');

test('admin.curricula.store 등록', function () {
    $parentCurriculum = Curriculum::whereIsRoot()->first();
    $this->actingAs($this->admin, 'admin')->post(route('admin.curricula.store'), [
        'parent_id' => $parentCurriculum->id,
        'name' => fake()->word()
    ])->assertSuccessful()
        ->assertJsonPath('success', true)
        ->assertJsonStructure([
            'success',
            'data' => [
                'curriculum',
            ]
        ]);
})->group('api');

test('admin.curricula.destroy 삭제', function () {
    $curriculum = Curriculum::factory()->create();
    $this->actingAs($this->admin, 'admin')->delete(route('admin.curricula.destroy', $curriculum))
        ->assertSuccessful()
        ->assertJsonPath('success', true)
        ->assertJsonStructure([
            'success',
            'data' => []
        ]);
})->group('api');

test('admin.curricula.update 수정', function () {
    $curriculum = Curriculum::factory()->create();
    $name = fake()->word();
    $this->actingAs($this->admin, 'admin')->put(route('admin.curricula.update', $curriculum), [
        'name' => $name
    ])->assertSuccessful()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.curriculum.name', $name)
        ->assertJsonStructure([
            'success',
            'data' => [
                'curriculum',
            ]
        ]);
})->group('api');


test('admin.curricula.update 이동', function () {
    $curriculum = Curriculum::factory()->state(['name' => 'test1'])->create();
    $curriculum2 = Curriculum::create([
        'name' => 'test2',
        'children' => [
            ['name' => 'position1'],
            ['name' => 'position2'],
            ['name' => 'position3'],
        ],
    ]);
    ;

    $this->actingAs($this->admin, 'admin')->put(route('admin.curricula.update', $curriculum), [
        'position' => 2,
        'parent_id' => $curriculum2->id
    ])->assertSuccessful()
        ->assertJsonPath('success', true)
        ->assertJsonStructure([
            'success',
            'data' => [
                'curriculum',
            ]
        ]);
    $curriculum->refresh();
})->group('api');


test('admin.curricula.index 학습목록-전체', function () {
    $this->actingAs($this->admin, 'admin')->get(route('admin.curricula.index'))
        ->assertInertia(
            fn (Assert $page) => $page
            ->component('curricula/Index')
            ->hasAll(['page', 'curriculum_depth_1', 'curriculum_depth_2'])
        );
})->group('admin', 'curricula');


test('admin.curricula.index 학습목록-필터', function () {
    $this->actingAs($this->admin, 'admin')->get(route('admin.curricula.index', [
        'filters[category_depth_1_id]' => 24,
        'filters[category_depth_2_id]' => 31,
    ]))
        ->assertInertia(
            fn (Assert $page) => $page
            ->component('curricula/Index')
            ->hasAll(['page', 'curriculum_depth_1'])
            ->has('curriculum_depth_2', 2)
            ->where('collection.meta.total', 2)
        );
})->group('admin', 'curricula');


test('admin.curricula.show 학습상세', function () {
    $curriculum = Curriculum::factory()->create();
    $this->actingAs($this->admin, 'admin')->get(route('admin.curricula.show', $curriculum))
        ->assertInertia(
            fn (Assert $page) => $page
            ->component('curricula/Show')
            ->has('page')
        );
})->group('admin', 'curricula');


test('admin.curricula.trainings.show 학습상세-트레이닝-메인 개념훈련', function () {
    $curriculum = Curriculum::factory()->create();
    Training::factory()->training1()->state([
        'curriculum_id' => $curriculum->id
    ])->create();

    Training::factory()->state([
        'curriculum_id' => $curriculum->id,
        'stage' => 1
    ])->create();

    $this->actingAs($this->admin, 'admin')->get(route('admin.curricula.trainings.show', [$curriculum, 1]))
        ->assertInertia(
            fn (Assert $page) => $page
            ->component('curricula/ShowTraining1TextReading') // 개념훈련메인-첫지문의 개념읽기
            ->hasAll(['contents', 'questions', 'training'])
        );
})->group('admin', 'curricula');

test('admin.curricula.training1.operations.show 학습상세-트레이닝 개념훈련-기초연산', function () {
    $curriculum = Curriculum::factory()->create();
    Training::factory()->state([
        'curriculum_id' => $curriculum->id,
        'stage' => 1
    ])->create();

    $this->actingAs($this->admin, 'admin')->get(route('admin.curricula.training1.operations.show', [$curriculum]))
        ->assertInertia(
            fn (Assert $page) => $page
            ->component('curricula/ShowTraining1Operations')
            ->has('page')
        );
})->group('admin', 'curricula');

test('admin.curricula.training1.operations.update 학습상세-트레이닝 개념훈련-기초연산', function () {
    $curriculum = Curriculum::factory()->create();
    Training::factory()->training1()->state([
        'curriculum_id' => $curriculum->id
    ])->create();

    $this->actingAs($this->admin, 'admin')->put(route('admin.curricula.training1.operations.update', [$curriculum]), [
        'basic_operations' => [
            'questions' => []
        ],
    ])
        ->assertSessionHasNoErrors()
        ->assertSessionHas('message', function ($value) {
            return is_array($value) && in_array('success', $value);
        })
        ->assertRedirectToRoute('admin.curricula.training1.operations.show', [$curriculum]);

    $training = $curriculum->trainings()->where('stage', 1)->first();
    $this->assertNotNull($training->contents['basic_operations']);
})->group('admin', 'curricula', 'training');


test('admin.curricula.training1.texts.show 학습상세-트레이닝 개념훈련-지문 개념읽기', function () {
    $curriculum = Curriculum::factory()->create();
    $training = Training::factory()->training1()->state([
        'curriculum_id' => $curriculum->id
    ])->create();
    $trainingConceptText = $training->training_concept_texts()->first();

    $this->actingAs($this->admin, 'admin')->get(route('admin.curricula.training1.texts.show', [$curriculum, $trainingConceptText, 'readings']))
        ->assertInertia(
            fn (Assert $page) => $page
            ->component('curricula/ShowTraining1TextReading')
            ->has('page')
        );
})->group('admin', 'curricula');

test('admin.curricula.training1.texts.show 학습상세-트레이닝 개념훈련-지문 개념요약', function () {
    $curriculum = Curriculum::factory()->create();
    $training = Training::factory()->training1()->state([
        'curriculum_id' => $curriculum->id
    ])->create();

    $trainingConceptText = $training->training_concept_texts()->create();
    $questions = Question::factory(2)->create();
    $training->questions()->saveMany($questions);

    $training->questions()->sync([
        $questions[0]->id => [
            'extra' => [
                'type' => 'training_concept_texts.summarizations',
                'model' => TrainingConceptText::class,
                'model_id' => $trainingConceptText->id
            ]
        ],
        $questions[1]->id => [
            'extra' => [
                'type' => 'training_concept_texts.summarizations',
                'model' => TrainingConceptText::class,
                'model_id' => $trainingConceptText->id
            ]
        ]
    ]);

    $this->actingAs($this->admin, 'admin')->get(route('admin.curricula.training1.texts.show', [$curriculum, $trainingConceptText, 'summarizations']))
        ->assertInertia(
            fn (Assert $page) => $page
            ->component('curricula/ShowTraining1TextSummarization')
            ->has('page')
        );
})->group('admin', 'curricula');


/*
test('admin.curricula.show 학습상세(트레이닝1)', function () {
    $curriculum = Curriculum::factory()->create();
    Training::factory()->training1()->state([
        'curriculum_id' => $curriculum->id
    ])->create();

    $this->actingAs($this->admin, 'admin')->get(route('admin.curricula.show', [$curriculum, 1]))
        ->assertInertia(fn(Assert $page) => $page
            ->component('curricula/ShowTraining1')
            ->has('page')
        );
})->group('admin', 'curricula');
*/

test('admin.curricula.training1.texts.update 개념훈련-개념읽기 등록', function () {
    $curriculum = Curriculum::factory()->create();
    $training = Training::factory()->training1()->state([
        'curriculum_id' => $curriculum->id
    ])->create();
    $trainingConceptText = $training->training_concept_texts()->first();


    $text = '안녕하세요//밤바람이 차네요//반갑습니다';
    $arrText = explode('//', $text);
    $readings = [
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
                'id' => Question::factory()->create()->id
            ]
        ]
    ];


    $this->actingAs($this->admin, 'admin')->put(route('admin.curricula.training1.texts.update', [$curriculum, $trainingConceptText, 'readings']), [
        'readings' => $readings
    ])
        ->assertSessionHasNoErrors()
        ->assertSessionHas('message', function ($value) {
            return is_array($value) && in_array('success', $value);
        })
        ->assertRedirectToRoute('admin.curricula.training1.texts.show', [$curriculum, $trainingConceptText, 'readings']);

    $this->assertNotNull($trainingConceptText->readings);
})->group('admin', 'curricula', 'training');

test('admin.curricula.training1.texts.update 개념훈련-개념요약 등록', function () {
    $curriculum = Curriculum::factory()->create();
    $training = Training::factory()->training1()->state([
        'curriculum_id' => $curriculum->id
    ])->create();
    $trainingConceptText = $training->training_concept_texts()->first();

    $questions = Question::factory(2)->create();
    $training->questions()->saveMany($questions);


    $this->actingAs($this->admin, 'admin')->put(route('admin.curricula.training1.texts.update', [$curriculum, $trainingConceptText, 'summarizations']), [
        'summarizations' => [
            'questions' => [
                [
                    'id' => $questions[0]->id
                ],
                [
                    'id' => $questions[1]->id
                ]
            ]
        ]
    ])
        ->assertSessionHasNoErrors()
        ->assertSessionHas('message', function ($value) {
            return is_array($value) && in_array('success', $value);
        })
        ->assertRedirectToRoute('admin.curricula.training1.texts.show', [$curriculum, $trainingConceptText, 'summarizations']);

    $trainingConceptText->refresh();
    $this->assertNotNull($trainingConceptText->summarizations);
})->group('admin', 'curricula', 'training');


test('admin.curricula.trainings.show 유형훈련 조회', function () {
    $curriculum = Curriculum::factory()->create();

    // 트레이닝 생성
    Training::factory()->state([
        'curriculum_id' => $curriculum->id
    ])->training2()->create();

    $this->actingAs($this->admin, 'admin')->get(route('admin.curricula.trainings.show', [$curriculum, 2]))
        ->assertInertia(
            fn (Assert $page) => $page
            ->component('curricula/ShowTraining2')
            ->has('page')
        );
})->group('admin', 'curricula');

test('admin.curricula.trainings.show 유형훈련 수정', function () {
    $curriculum = Curriculum::factory()->create();

    // 트레이닝 생성
    Training::factory()->state([
        'curriculum_id' => $curriculum->id
    ])->training2()->create();

    $this->actingAs($this->admin, 'admin')->put(route('admin.curricula.trainings.update', [$curriculum, 2]), [
        'contents' => [
            [
                Question::factory()->create()->id,
            ],
            [
                Question::factory()->create()->id,
            ],
            [],
            []
        ],
    ])
        ->assertSessionHasNoErrors()
        ->assertSessionHas('message', function ($value) {
            return is_array($value) && in_array('success', $value);
        })
        ->assertRedirectToRoute('admin.curricula.trainings.show', [$curriculum, 2]);

    $this->assertDatabaseHas(Training::class, [
        'id' => 1,
        'stage' => 2,
        'curriculum_id' => $curriculum->id,
    ]);
})->group('admin', 'questions');

test('admin.curricula.trainings.show 서술형훈련 조회', function () {
    $curriculum = Curriculum::factory()->create();

    // 트레이닝 생성
    Training::factory()->state([
        'curriculum_id' => $curriculum->id
    ])->training3()->create();

    $this->actingAs($this->admin, 'admin')->get(route('admin.curricula.trainings.show', [$curriculum, 3]))
        ->assertInertia(
            fn (Assert $page) => $page
            ->component('curricula/ShowTraining3')
            ->has('page')
        );

    $this->assertDatabaseHas(Training::class, [
        'id' => 1,
        'stage' => 3,
        'curriculum_id' => $curriculum->id,
    ]);
})->group('admin', 'curricula');


test('admin.curricula.trainings.show 서술형훈련 수정', function () {
    $curriculum = Curriculum::factory()->create();

    // 트레이닝 생성
    Training::factory()->state([
        'curriculum_id' => $curriculum->id
    ])->training3()->create();

    $this->actingAs($this->admin, 'admin')->put(route('admin.curricula.trainings.update', [$curriculum, 3]), [
        'contents' => [
            [
                Question::factory()->create()->id,
            ],
            [
                'sheets' => [
                    [
                        [
                            'type' => 0,
                            'text' => fake()->text(),
                        ],
                        [
                            'type' => 1,
                            'text' => fake()->text(),
                            'question' => [
                                'choices' => [
                                    fake()->sentence(),
                                    fake()->sentence(),
                                    fake()->sentence(),
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            [
                Question::factory()->create()->id,
            ]
        ],
    ])
        ->assertSessionHasNoErrors()
        ->assertSessionHas('message', function ($value) {
            return is_array($value) && in_array('success', $value);
        })
        ->assertRedirectToRoute('admin.curricula.trainings.show', [$curriculum, 3]);

    $this->assertDatabaseHas(Training::class, [
        'id' => 1,
        'stage' => 3,
        'curriculum_id' => $curriculum->id,
    ]);
})->group('admin', 'questions');

test('admin.curricula.texts.destroy 삭제', function () {
    $curriculum = Curriculum::factory()->create();
    $training = Training::factory()->training1()->state([
        'curriculum_id' => $curriculum->id
    ])->create();
    $trainingConceptText = $training->training_concept_texts()->first();
    $this->actingAs($this->admin, 'admin')->delete(route('admin.curricula.texts.destroy', [$curriculum, $trainingConceptText]))
        ->assertSessionHasNoErrors()
        ->assertSessionHas('message', function ($value) {
            return is_array($value) && in_array('success', $value);
        })
        ->assertRedirect(route('admin.curricula.show', [$curriculum, 1]));

    $this->assertModelMissing($trainingConceptText);
})->group('api');
