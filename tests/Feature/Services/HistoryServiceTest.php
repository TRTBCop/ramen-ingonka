<?php

use App\Models\Curriculum;
use App\Models\Student;
use App\Models\Training;
use App\Models\TrainingResult;
use App\Services\HistoryService;
use Database\Seeders\CurriculumSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;


uses(RefreshDatabase::class)->beforeEach(function () {
    $this->seed(CurriculumSeeder::class);
    $this->ref = new ReflectionClass(HistoryService::class);
});

test('HistoryService.getItems 학습 기록 가져오기', function () {
    $student = Student::factory()->create()->first();

    Training::factory(30)->create();

    TrainingResult::factory(50)->state(function () use ($student) {
        return [
            'curriculum_id' => Curriculum::inRandomOrder()->first()->id,
            'student_id' => $student['id'],
            'training_id' => Training::inRandomOrder()->first()->id,
        ];
    })->create();

    /** @see HistoryService::getItems() */
    $method = $this->ref->getMethod('getItems');
    $method->setAccessible(true);
    $result = $method->invoke(
        new HistoryService($student),
        now()->format('Y-m-d'),
        now()->subDays(10)->format('Y-m-d')
    );

    $this->assertArrayHasKey('week', $result);
    $this->assertArrayHasKey('four_weeks', $result);
    $this->assertEquals(4, count($result['four_weeks']));
    $this->assertArrayHasKey('trainings', $result);
    foreach ($result['trainings'] as $value) {
        if (
            Arr::has($value, 'subjects') &&
            Arr::has($value, 'total') &&
            Arr::has($value, 'stages')
        ) {
            $this->assertTrue(true);
        } else {
            $this->assertTrue(false);
        }
    }
});
