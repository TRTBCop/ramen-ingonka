<?php

use App\Models\Student;
use App\Models\StudentPhone;
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

test('.show 내정보', function () {
    $this->actingAs($this->student)->get(route('app.my.profile.show'))
        ->assertInertia(
            fn (Assert $page) => $page
            ->component('my/Profile')
        );
})->group('app', 'main');

test('app.my 내정보 수정', function () {
    $parentsPhone = fake()->numerify('010########');

    $studentPhone = StudentPhone::create([
        'phone' => $parentsPhone,
        'code' => '0000',
        'verified' => 1,
    ]);

    $this->actingAs($this->student)->put(route('app.my.profile.update'), [
        'birth_date' => fake()->date(),
        'parents_name' => fake()->name(),
        'parents_phone' => $parentsPhone,
        'student_phone_id' => $studentPhone->id,
        'marketing_consent' => false
    ])
        ->assertSessionHasNoErrors()
        ->assertSessionHas('message', function ($value) {
            return is_array($value) && in_array('success', $value);
        })
        ->assertRedirect(route('app.my.profile.show'));

    $this->assertDatabaseHas(Student::class, [ // 학생상태변경 체크
        'id' => $this->student->id,
        'parents_phone' => $parentsPhone,
    ]);
})->group('app', 'main');


test('app.my.social.update 소셜연동 해제', function () {
    $this->student->update([
        'naver_id' => '1234'
    ]);

    $this->actingAs($this->student)->post(route('app.my.social.update', [
        'naver'
    ]))
        ->assertSessionHasNoErrors()
        ->assertSessionHas('message', function ($value) {
            return is_array($value) && in_array('success', $value);
        })
        ->assertRedirect(route('app.my.profile.show'));

    $this->assertDatabaseHas(Student::class, [ // 학생상태변경 체크
        'id' => $this->student->id,
        'naver_id' => '',
    ]);
})->group('app', 'main');


test('app.my.social.update 소셜연동', function () {
    $this->actingAs($this->student)->post(route('app.my.social.update', [
        'naver'
    ]))
        ->assertSessionHasNoErrors()
        ->assertSessionHas('social_redirect_url', route('app.my.profile.show'))
        ->assertRedirect(route('auth.social.login', 'naver'));
})->group('app', 'main');

test('app.my.grade-term.update 학년학기설정', function () {
    $this->student = Student::factory()->create();

    $this->actingAs($this->student)->patch(route('app.my.grade-term.update', [
        'grade' => 3,
        'term' => 1
    ]))
        ->assertSessionHasNoErrors()
        ->assertSessionHas('message', function ($value) {
            return is_array($value) && in_array('success', $value);
        })
        ->assertRedirect(route('app.main'));

    $this->assertDatabaseHas(Student::class, [ // 학생상태변경 체크
        'grade' => 3,
        'term' => 1
    ]);
})->group('app', 'main');
