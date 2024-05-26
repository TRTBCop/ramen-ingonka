<?php

use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class)->beforeEach(function () {
    Artisan::call('db:seed');
});

test('app.auth.create 로그인', function () {
    $this->get(route('app.auth.create'))
        ->assertInertia(fn(Assert $page) => $page
            ->component('auth/Login')
            ->has('social_url.naver')
            ->has('social_url.kakao')
        );
})->group('app', 'auth');

test('app.auth.store 로그인처리 (성공)', function () {
    $accessId = 'student';
    $password = '1234';

    Student::factory()->state([
        'access_id' => $accessId,
        'password' => bcrypt($password),
    ])->create();

    $response = $this->post(route('app.auth.store'), [
        'access_id' => $accessId,
        'password' => $password,
        'remember' => true
    ]);

    $this->assertAuthenticated(); // 인증체크
    $response->assertRedirect(route('app.main'));
})->group('app', 'auth');


test('admin.logout action 로그아웃', function () {
    $student = Student::factory()->create();

    $response = $this->actingAs($student)->post(route('app.auth.destroy'));

    $this->assertGuest(); // 인증체크
    $response->assertRedirect(route('app.auth.create')); // 로그인 페이지로 이동하는가
})->group('app', 'auth');