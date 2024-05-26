<?php

namespace Admin;

use App\Models\Admin;
use App\Models\Curriculum;
use App\Models\Question;
use App\Models\Test;
use App\Models\Training;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class)->beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
    $this->admin = Admin::factory()->create()->assignRole('super');
    Storage::fake('s3');
});

test('admin.questions.index 목록-전체', function () {
    Question::factory(20)->create();
    $this->actingAs($this->admin, 'admin')
        ->withHeaders([
            'X-Inertia' => false,
            'X-Requested-With' => 'XMLHttpRequest',
        ])
        ->get(route('admin.questions.index'))
        ->assertInertia(
            fn (Assert $page) => $page
            ->component('questions/Index')
            ->where('collection.meta.total', 20)
            ->has('page')
        );
})->group('admin', 'questions');

test('admin.questions.index 목록-검색 return type json', function () {
    Question::factory(10)->create();
    $question = Question::first();
    $this->actingAs($this->admin, 'admin')->json('GET', route('admin.questions.index', [
        'filter_text' => $question->title,
    ]))->assertSuccessful()
        ->assertJsonPath('success', true)
        ->assertJsonStructure([
            'success',
            'data' => [
                'collection',
            ]
        ]);
})->group('admin', 'questions');

/*
test('admin.questions.index 목록-필터', function () {
    Question::factory(20)->create();

    $questions = Question::find(1);

    $this->actingAs($this->admin, 'admin')->get(route('admin.questions.index', [
        'filters[type]' => [$questions->type->value],
        'filters[tags]' => [$questions->tags()->first()->name],
        'filter_text' => $questions->title,
    ]))
        ->assertInertia(fn(Assert $page) => $page
            ->component('questions/Index')
            ->where('collection.meta.total', 1)
            ->has('page')
        );
})->group('admin', 'questions');
*/

test('admin.questions.store 등록처리 기본', function () {
    $curriculum = Curriculum::factory()->create();
    $this->actingAs($this->admin, 'admin')->post(route('admin.questions.store'), [
        'is_published' => true,
        'curriculum_id' => $curriculum->id,
        'question' => fake()->text(),
        'inquiry' => fake()->text(),
        'options' => fake()->text(),
        'answers' => [
            [
                'type' => 1,
                'action' => 1,
                'answer' => ['1'],
                'choice_symbol' => true
            ],
            [
                'type' => 2,
                'action' => 1,
                'answer' => ['1'],
                'choices' => [
                    fake()->sentence()
                ]
            ],
        ],
        'tags' => [
            'default' => [
                '태그1',
                '태그2',
            ],
            'used_at' => [
                '진단평가',
            ],
        ],
        'explanation' => fake()->text()
    ])
        ->assertSuccessful()
        ->assertJsonPath('success', true)
        ->assertJsonStructure([
            'success',
            'data' => [
                'question',
            ]
        ]);

    // 생성 체크
    $this->assertDatabaseHas(Question::class, [
        'id' => 1,
    ]);
})->group('admin', 'questions');

test('admin.questions.store 등록처리 연관테이블', function () {
    $curriculum = Curriculum::factory()->create();
    $test = Test::factory()->create();

    $this->actingAs($this->admin, 'admin')->post(route('admin.questions.store'), [
        'is_published' => true,
        'curriculum_id' => $curriculum->id,
        'question' => fake()->text(),
        'inquiry' => fake()->text(),
        'options' => fake()->text(),
        'answers' => [
            [
                'type' => 1,
                'action' => 1,
                'answer' => ['1'],
                'choice_symbol' => true
            ],
            [
                'type' => 2,
                'action' => 1,
                'answer' => ['1'],
                'choices' => [
                    fake()->sentence()
                ]
            ],
        ],
        'explanation' => fake()->text(),
        'tags' => [
            'default' => [
                '태그1',
                '태그2',
            ],
            'used_at' => [
                '진단평가',
            ],
        ],
        'rel' => [
            'table' => 'tests',
            'id' => $test->id,
            'extra' => [
                'sort' => 10,
                'is_extend' => 1
            ]
        ]
    ])
        ->assertSuccessful()
        ->assertJsonPath('success', true)
        ->assertJsonStructure([
            'success',
            'data' => [
                'question',
            ]
        ]);

    // 생성 체크
    $this->assertDatabaseHas(Question::class, [
        'id' => 1,
    ]);
})->group('admin', 'questions');


test('admin.questions.show 상세', function () {
    $question = Question::factory()->create();
    $training = Training::factory()->training1()->create();
    $question->trainings()->attach($training);


    $this->actingAs($this->admin, 'admin')->json('GET', route('admin.questions.show', $question))
        ->assertSuccessful()
        ->assertJsonPath('success', true)
        ->assertJsonStructure([
            'success',
            'data' => [
                'question',
            ]
        ]);
})->group('admin', 'questions');


test('admin.questions.update 수정처리', function () {
    $path1 = Storage::disk('s3')->put('temp/images', UploadedFile::fake()->image('test.png'));
    $url = Storage::disk('s3')->url($path1);

    $path2 = Storage::disk('s3')->put('temp/images', UploadedFile::fake()->image('test.png'));
    $url2 = Storage::disk('s3')->url($path2);

    $question = Question::factory()->create();

    $inquiry = fake()->sentence();
    $this->actingAs($this->admin, 'admin')->put(route('admin.questions.update', $question), [
        'question' => fake()->text().'<img src="'.$url.'" >',
        'options' => fake()->text().'<img src="'.$url2.'" >',
        'inquiry' => $inquiry,
        'answers' => [
            [
                'type' => 1,
                'action' => 1,
                'answer' => ['1'],
                'choice_symbol' => true
            ],
            [
                'type' => 2,
                'action' => 1,
                'answer' => ['1'],
                'choices' => [
                    fake()->sentence()
                ]
            ],
        ],
        'explanation' => fake()->text(),
        'tags' => [
            'default' => [
                '태그1',
                '태그2',
            ],
            'used_at' => [
                '진단평가',
            ],
        ],
    ])
        ->assertSuccessful()
        ->assertJsonPath('success', true)
        ->assertJsonStructure([
            'success',
            'data' => [
                'question',
            ]
        ]);

    // 수정 체크
    $this->assertDatabaseHas(Question::class, [
        'id' => 1,
        'inquiry' => $inquiry
    ]);
})->group('admin', 'questions');


test('admin.questions.destroy 삭제', function () {
    $question = Question::factory()->create();
    $this->actingAs($this->admin, 'admin')->delete(route('admin.questions.destroy', $question))
        ->assertSessionHasNoErrors()
        ->assertSessionHas('message', function ($value) {
            return is_array($value) && in_array('success', $value);
        })
        ->assertRedirect(route('admin.questions.index'));

    $this->assertSoftDeleted($question);
})->group('admin', 'questions');
