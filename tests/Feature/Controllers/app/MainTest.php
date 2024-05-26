<?php

use App\Models\Student;
use App\Models\Training;
use App\Models\TrainingResult;
use Database\Seeders\CurriculumSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class)->beforeEach(function () {
    $this->seed(CurriculumSeeder::class);

    $this->student = Student::factory()->state([
        'grade' => 3,
        'term' => 1
    ])->create();
});

test('app.main 표준모드-아무런 학습이 없는경우 첫학습을가저온다', function () {
    $this->actingAs($this->student)->get(route('app.main'))
        ->assertInertia(
            fn (Assert $page) => $page
            ->component('main/Show')
            ->where('today_curriculum.id', 32) // 받아올림이 없는 세 자리 수의 덧셈
        );
})->group('app', 'main');

test('app.main 표준모드-어제학습은완료하고 오늘학습이 없는경우 완료한 학습 다음학습을 가저온다', function () {
    $curriculumId = 32;
    $training = Training::factory()->training1()->state([
        'curriculum_id' => $curriculumId
    ])->create();

    // 어제완료 피벗테이블 등록
    $this->student->curricula()->attach($curriculumId, [
        'completed_at' => now()->subDay(),
        'updated_at' => now()->subDay(),
        'created_at' => now()->subDay()
    ]);

    $this->actingAs($this->student)->get(route('app.main'))
        ->assertInertia(
            fn (Assert $page) => $page
            ->component('main/Show')
            ->where('today_curriculum.id', 33) // 받아올림이 있는 세 자리 수의 덧셈
        );
})->group('app', 'main');

test('app.main 표준모드-어제학습을 개념학습만하고 완료하지 안은상태에서 오늘완료 했을때 오늘의 학습', function () {
    $curriculumId = 32;
    $training = Training::factory()->training1()->state([
        'curriculum_id' => $curriculumId
    ])->create();

    // 어제완료 피벗테이블 등록
    $this->student->curricula()->attach($curriculumId, [
        'created_at' => now()->subDay()
    ]);

    // 오늘완료함
    $this->student->curricula()->updateExistingPivot($curriculumId, [
        'completed_at' => now()
    ]);

    TrainingResult::factory()->state([
        'curriculum_id' => $curriculumId,
        'student_id' => $this->student->id,
        'training_id' => $training->id,
        'completed_at' => now()->subDay()
    ])->create();

    $this->actingAs($this->student)->get(route('app.main'))
        ->assertInertia(
            fn (Assert $page) => $page
            ->component('main/Show')
            ->where('today_curriculum.id', 32) // 받아올림이 없는 세 자리 수의 덧셈
            ->where('next_curriculum_id', 33) // 받아올림이 있는 세 자리 수의 덧셈
        );
})->group('app', 'main');

test('app.main 표준모드-오늘의학습을 완료후 다음학습', function () {
    $curriculumId = 32;
    $nextCurriculumId = 33;
    $training = Training::factory()->training1()->state([
        'curriculum_id' => $nextCurriculumId
    ])->create();

    // 오늘완료 피벗테이블 등록
    $this->student->curricula()->attach($curriculumId, [
        'created_at' => now(),
        'completed_at' => now()
    ]);

    // 다음까지 완료함
    $this->student->curricula()->attach($nextCurriculumId, [
        'created_at' => now(),
        'completed_at' => now()
    ]);

    TrainingResult::factory()->state([
        'curriculum_id' => $nextCurriculumId,
        'student_id' => $this->student->id,
        'training_id' => $training->id,
        'completed_at' => now()
    ])->create();

    $this->actingAs($this->student)->get(route('app.main', [
        $nextCurriculumId
    ]))
        ->assertInertia(
            fn (Assert $page) => $page
            ->component('main/Show')
            ->where('today_curriculum.id', $nextCurriculumId) // 받아올림이 있는 세 자리 수의 덧셈
            ->where('next_curriculum_id', $nextCurriculumId + 1)
        );
})->group('app', 'main');


test('app.main.free 자유모드', function () {
    $this->actingAs($this->student)->get(route('app.main.free'))
        ->assertInertia(
            fn (Assert $page) => $page
            ->component('main/Free')
            ->hasAll(['curriculaMap', 'trainingResults'])
        );
})->group('app', 'main');
