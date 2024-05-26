<?php

use App\Enums\StudentStatusEnum;
use App\Models\Student;
use App\Models\Test;
use App\Models\TestResult;
use Database\Seeders\CurriculumSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class)->beforeEach(function () {
    $this->seed(CurriculumSeeder::class);

    $this->student = Student::factory()->state([
        'status' => StudentStatusEnum::FREE,
        'grade' => 3,
        'term' => 1
    ])->create();
});

test('app.tests.index 진단평가 메인', function () {
    $this->actingAs($this->student)->get(route('app.tests.index'))
        ->assertInertia(
            fn (Assert $page) => $page
            ->component('tests/Index')
            ->has('test_group')
        );
})->group('app', 'tests');


test('app.tests.show 진단평가 테스트-완료된 학습을 진행시', function () {
    $test = Test::factory()->create();
    TestResult::factory()->state([
        'test_id' => $test->id,
        'student_id' => $this->student->id,
        'completed_at' => now()
    ])->create();

    $this->actingAs($this->student)->get(route('app.tests.show', $test))
        ->assertSessionHas('message', function ($value) {
            return is_array($value) && in_array('error', $value) && in_array(__('messages.app.tests.completed'), $value);
        })
        ->assertRedirectToRoute('app.tests.index');
})->group('app', 'tests');

test('app.tests.show 진단평가 테스트-무료체험회원이 다른진단평가 진행시', function () {
    $test1 = Test::factory()->create();
    $test2 = Test::factory()->create();

    TestResult::factory()->state([
        'test_id' => $test1->id,
        'student_id' => $this->student->id,
        'completed_at' => now()
    ])->create();

    $this->actingAs($this->student)->get(route('app.tests.show', $test2))
        ->assertSessionHas('message', function ($value) {
            return is_array($value) && in_array('error', $value) && in_array(__('messages.app.tests.ticket_purchase_required'), $value);
        })
        ->assertRedirectToRoute('app.tests.index');
})->group('app', 'tests');
