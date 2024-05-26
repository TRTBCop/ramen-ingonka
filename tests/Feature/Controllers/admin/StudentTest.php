<?php

namespace Admin;

use App\Models\Academy;
use App\Models\Admin;
use App\Models\Student;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class)->beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
    $this->admin = Admin::factory()->create()->assignRole('super');
    Storage::fake('media');
});


test('admin.students.index 학생목록', function () {
    Student::factory(20)->state([
        'academy_id' => null
    ])->create();
    Student::factory(10)->state([
        'academy_id' => Academy::factory()
    ])->create();

    $this->actingAs($this->admin, 'admin')->get(route('admin.students.index', [
        'filters[b2c]' => true,
    ]))
        ->assertInertia(
            fn (Assert $page) => $page
                ->component('student/Index')
                ->where('collection.meta.total', 20)
                ->has('page')
        );
})->group('admin', 'students');


test('admin.students.show 학생상세', function () {
    $student = Student::factory()->create();

    $this->actingAs($this->admin, 'admin')->get(route('admin.students.show', $student))
        ->assertInertia(
            fn (Assert $page) => $page
                ->component('student/Show')
                ->has('student')
        );
})->group('admin', 'students');


test('admin.students.store 등록', function () {
    $accessId = 'dailykor';
    $this->actingAs($this->admin, 'admin')->post(route('admin.students.store'), [
        'password' => '1234aaa!',
        'name' => '홍길동',
        'access_id' => $accessId,
        'parents_phone' => '01000000000'
    ])
        ->assertSessionHasNoErrors()
        ->assertSessionHas('message', function ($value) {
            return is_array($value) && in_array('success', $value);
        })
        ->assertRedirect(route('admin.students.show', 1));

    $this->assertDatabaseHas('students', [ // 학생등록 체크
        'access_id' => $accessId,
    ]);
})->group('admin', 'academies');


test('admin.students.update 수정', function () {
    $student = Student::factory()->create()->refresh();

    $this->actingAs($this->admin, 'admin')->put(route('admin.students.update', $student), [
        'password' => '',
        'name' => '홍길동',
        'access_id' => 'dailykor',
    ])
        ->assertSessionHasNoErrors()
        ->assertSessionHas('message', function ($value) {
            return is_array($value) && in_array('success', $value);
        })
        ->assertRedirect(route('admin.students.show', $student));

    $student->refresh();
    $this->assertTrue(str_contains($student->avatar, 'media'));
    $this->assertDatabaseHas('students', [ // 학생상태변경 체크
        'id' => $student->id,
        'name' => '홍길동',
        'access_id' => $student->access_id,
    ]);
})->group('admin', 'academies');


test('admin.students.destroy 삭제', function () {
    $student = Student::factory()->create();
    $this->actingAs($this->admin, 'admin')->delete(route('admin.students.destroy', $student))
        ->assertSessionHasNoErrors()
        ->assertSessionHas('message', function ($value) {
            return is_array($value) && in_array('success', $value);
        })
        ->assertRedirect(route('admin.students.index'));

    $this->assertSoftDeleted($student);
})->group('admin', 'academies');
