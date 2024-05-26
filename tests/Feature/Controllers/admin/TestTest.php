<?php

namespace Admin;

use App\Models\Admin;
use App\Models\Question;
use App\Models\Test;
use Database\Seeders\CurriculumSeeder;
use Database\Seeders\InitSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class)->beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
    $this->seed(InitSeeder::class);
    $this->seed(CurriculumSeeder::class);
    $this->admin = Admin::factory()->create()->assignRole('super');
    Storage::fake('s3');
});

test('admin.tests.index 진단목록(전체)', function () {
    $this->actingAs($this->admin, 'admin')->get(route('admin.tests.index'))
        ->assertInertia(
            fn (Assert $page) => $page
            ->component('tests/Index')
            ->has('page')
        );
})->group('admin', 'tests');


test('admin.tests.index 진단목록(필터)', function () {
    $test = Test::find(1);
    $test->published_at = now()->format('Y-m-d H:i:s');
    $test->save();

    $this->actingAs($this->admin, 'admin')->get(route('admin.tests.index', [
        'filters[published_at]' => true,
    ]))
        ->assertInertia(
            fn (Assert $page) => $page
            ->component('tests/Index')
            ->where('collection.meta.total', 1)
            ->has('page')
        );
})->group('admin', 'tests');

test('admin.tests.show 진단상세', function () {
    $test = Test::find(1);
    $question = Question::factory()->create();
    $test->questions()->attach($question->id);

    $question2 = Question::factory()->create();
    $test->questions()->attach($question2->id);

    $question3 = Question::factory()->create();
    $test->questions()->attach($question3->id);

    $test->contents = [
        'questions' => [
            ['id' => $question->id, 'is_extend' => 0],
            ['id' => $question3->id, 'is_extend' => 1],
            ['id' => $question2->id, 'is_extend' => 0],
        ]
    ];
    $test->save();


    $this->actingAs($this->admin, 'admin')->get(route('admin.tests.show', $test))
        ->assertInertia(
            fn (Assert $page) => $page
            ->component('tests/Show')
            ->has('page')
        );
})->group('admin', 'tests');

test('admin.tests.update 진단수정', function () {
    $test = Test::find(1);
    $this->actingAs($this->admin, 'admin')->put(route('admin.tests.update', [$test]), [
        'contents' => [
            'questions' => [
                [
                    'id' => 1,
                ]
            ]
        ],
        'is_published' => true
    ])
        ->assertSessionHasNoErrors()
        ->assertSessionHas('message', function ($value) {
            return is_array($value) && in_array('success', $value);
        })
        ->assertRedirectToRoute('admin.tests.show', [$test]);

    $test->refresh();

    $this->assertTrue(isset($test->published_at));
})->group('admin', 'tests');


test('admin.tests.destroy 삭제', function () {
    $test = Test::find(1);

    $this->actingAs($this->admin, 'admin')->delete(route('admin.tests.destroy', [$test]))
        ->assertSessionHasNoErrors()
        ->assertSessionHas('message', function ($value) {
            return is_array($value) && in_array('success', $value);
        })
        ->assertRedirect(route('admin.tests.index'));

    $this->assertModelMissing($test);
})->group('admin', 'tests');

test('admin.tests.destroy 삭제 fail', function () {
    $test = Test::find(1);
    $question = Question::factory()->create();
    $test->questions()->attach($question->id, ['extra' => ['sort' => 1]]);

    $this->actingAs($this->admin, 'admin')->delete(route('admin.tests.destroy', [$test]))
        ->assertSessionHasNoErrors()
        ->assertSessionHas('message', function ($value) {
            return $value[1] == __('messages.admin.tests.child_exists_cannot_delete');
        });
})->group('admin', 'tests');
