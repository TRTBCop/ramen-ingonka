<?php

namespace Tests\Feature\Controllers\app;

use App\Enums\StudentStatusEnum;
use App\Models\Curriculum;
use App\Models\Student;
use App\Models\Training;
use App\Models\TrainingResult;
use Database\Seeders\CurriculumSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class)->beforeEach(function () {
    $this->seed(CurriculumSeeder::class);

    $student = Student::factory()->state([
        'status' => StudentStatusEnum::FREE,
        'grade' => 3,
        'term' => 1,
    ])->create();

    Training::factory(30)->create();

    TrainingResult::factory(50)->state(function () use ($student) {
        return [
            'curriculum_id' => Curriculum::inRandomOrder()->first()->id,
            'student_id' => $student['id'],
            'training_id' => Training::inRandomOrder()->first()->id,
        ];
    })->create();

    $this->student = $student;
});

test('api.training.history 학습 기록 (성공)', function () {
    $response = $this->actingAs($this->student)->get(route('app.training.history'));
    $response->assertInertia(fn(Assert $page) => $page
        ->component('training/History')
        ->hasAll(['history'])
    );
})->group('training');