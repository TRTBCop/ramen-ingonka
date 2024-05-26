<?php

use App\Models\Test;

//use App\Models\TestQuestion;
use App\Models\TestResult;
use App\Services\TestService;
use Database\Seeders\CurriculumSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class)->beforeEach(function () {
    $this->seed(CurriculumSeeder::class);
    $this->ref = new ReflectionClass(TestService::class);
});

test('TestService.setReport 리포트생성', function () {
    $test = Test::factory()->create();

    /*
     // todo test_questions => questions 로직으로 변경
    // 기본문제
    TestQuestion::factory(2)->state([
        'test_id' => $test->id,
        'published_at' => now()
    ])->create();

    TestQuestion::factory()->state([
        'test_id' => $test->id,
        'curriculum_id' => '32',
        'published_at' => now()
    ])->create();

    //추가문제
    TestQuestion::factory(2)->state([
        'test_id' => $test->id,
        'is_extend' => 1,
        'published_at' => now()
    ])->create();
    */

    $questions = $test->questions()->with('curriculum')->whereNotNull('published_at')->get();

    $student = \App\Models\Student::factory()->create();
    $testResult = TestResult::factory()->state([
        'test_id' => $test->id,
        'student_id' => $student->id,
        'completed_at' => now(),
        'extra' => [
            'timer' => 1111,
            'questions' => [
                1 => [
                    'id' => 1,
                    'is_extend' => 0,
                    'question_count' => 3,
                    'correct_count' => 3,
                    'element' => 700,
                    'answers' => [
                        [
                            'correct' => true,
                            'action' => 1
                        ]
                    ]
                ],
                2 => [
                    'id' => 2,
                    'is_extend' => 0,
                    'question_count' => 3,
                    'correct_count' => 2,
                    'element' => 700,
                    'answers' => [
                        [
                            'correct' => true,
                            'action' => 3
                        ],
                        [
                            'correct' => false,
                            'action' => 3
                        ]
                    ]
                ],
                3 => [
                    'id' => 3,
                    'is_extend' => 0,
                    'question_count' => 3,
                    'correct_count' => 3,
                    'element' => 600
                ],
                4 => [
                    'id' => 4,
                    'is_extend' => 1,
                    'question_count' => 3,
                    'correct_count' => 3,
                    'element' => 600
                ],
            ]
        ]
    ])->create();


    $curriculumService = new \App\Services\CurriculumService();

    /** @see TestService::setReport() */
    $method = $this->ref->getMethod('setReport');
    $method->setAccessible(true);
    $result = $method->invoke(new TestService($curriculumService), $questions, $testResult, [
        'test_title' => $test->title,
        'name' => $student->name
    ]);


    //$this->assertTrue(str_contains($result, '<img'));
});
