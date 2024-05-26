<?php

namespace Admin;

use App\Models\Admin;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class)->beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
});

test('admin.login form', function () {
    $this->get(route('admin.login'))
        ->assertInertia(
            fn (Assert $page) => $page
            ->component('auth/Login')
        );
});

test('admin.login.store [super] action', function () {
    $accessId = 'admin';
    $password = '1234';

    Admin::factory()->state([
        'access_id' => $accessId,
        'password' => bcrypt($password),
    ])->create()->assignRole('super');

    $response = $this->post(route('admin.login.store'), [
        'access_id' => $accessId,
        'password' => $password,
        'remember' => true
    ]);

    $this->assertAuthenticated('admin'); // 인증체크
    $response->assertRedirect(route('admin.dashboard'));
});

test('admin.login.store [content] action', function () {
    $accessId = 'admin';
    $password = '1234';

    Admin::factory()->state([
        'access_id' => $accessId,
        'password' => bcrypt($password),
    ])->create()->assignRole('contents');

    $response = $this->post(route('admin.login.store'), [
        'access_id' => $accessId,
        'password' => $password,
        'remember' => true
    ]);

    $this->assertAuthenticated('admin'); // 인증체크
    $response->assertRedirect(route('admin.dashboard'));
});


test('admin.login.store fail-validation', function () {
    $accessId = 'admin';
    $password = '1234';

    Admin::factory()->state([
        'access_id' => $accessId,
        'password' => bcrypt($password)
    ])->create();

    $this->post(route('admin.login'), [
        'password' => $password,
    ])->assertInvalid(['access_id']);
});

test('admin.profile.show form', function () {
    $admin = Admin::factory()->create()->assignRole('super');

    $this->actingAs($admin, 'admin')->get(route('admin.profile.show'))
        ->assertInertia(
            fn (Assert $page) => $page
            ->component('auth/Profile')
        );
});

test('admin.profile.update action', function () {
    $admin = Admin::factory()->create()->assignRole('super');

    $changeName = '1234';
    $changePassword = '1234';
    $response = $this->actingAs($admin, 'admin')->put(route('admin.profile.update'), [
        'avatar' => UploadedFile::fake()->image('avatar.jpg'),
        'remove_avatar' => true,
        'name' => $changeName,
        'password' => $changePassword
    ]);

    // 업로드 잘되었나 체크
    $this->assertTrue(str_contains($admin->avatar, 'media'));

    // 수정은되었나 체크
    $this->assertTrue($changeName == $admin->name);
    $this->assertTrue(Hash::check($changePassword, $admin->password));

    // 이름정보 변경되었나 체크
    $response->assertRedirect(route('admin.profile.show'));
});


test('admin.logout action', function () {
    $admin = Admin::factory()->create()->assignRole('super');

    $response = $this->actingAs($admin, 'admin')->post(route('admin.logout'));

    $this->assertGuest('admin'); // 인증체크
    $response->assertRedirect(route('admin.login')); // 로그인 페이지로 이동하는가
});
