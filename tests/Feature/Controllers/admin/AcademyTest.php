<?php

namespace Admin;

use App\Models\Academy;
use App\Models\Admin;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class)->beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
    $this->admin = Admin::factory()->create()->assignRole('super');
    Storage::fake('media');
});

test('admin.academies.index 목록-전체', function () {
    Academy::factory(20)->create();
    $this->actingAs($this->admin, 'admin')->get(route('admin.academies.index'))
        ->assertInertia(
            fn (Assert $page) => $page
            ->component('academy/Index')
            ->where('collection.meta.total', 20)
            ->has('page')
        );
})->group('admin', 'academies');


test('admin.academies.index 목록-필터', function () {
    Academy::factory(20)->create();

    $academy = Academy::find(1);

    $this->actingAs($this->admin, 'admin')->get(route('admin.academies.index', [
        'filters[status]' => [$academy->status->value],
        'filters[tags]' => [$academy->tags()->first()->name],
        'filter_text' => $academy->name,
    ]))
        ->assertInertia(
            fn (Assert $page) => $page
            ->component('academy/Index')
            ->where('collection.meta.total', 1)
            ->has('page')
        );
})->group('admin', 'academies');


test('admin.academies.export 엑셀다운로드', function () {
    Academy::factory(20)->create();

    $this->actingAs($this->admin, 'admin')->get(route('admin.academies.export', [
    ]))->assertDownload();
})->group('admin', 'academies');


test('admin.academies.destroy 삭제', function () {
    Academy::factory(20)->create();

    $academy = Academy::find(1);
    $this->actingAs($this->admin, 'admin')->delete(route('admin.academies.destroy', $academy))
        ->assertSessionHasNoErrors()
        ->assertSessionHas('message', function ($value) {
            return is_array($value) && in_array('success', $value);
        })
        ->assertRedirect(route('admin.academies.index'));

    $this->assertSoftDeleted($academy);
})->group('admin', 'academies');


test('admin.academies.create 등록폼', function () {
    $this->actingAs($this->admin, 'admin')->get(route('admin.academies.create'))
        ->assertInertia(
            fn (Assert $page) => $page
            ->component('academy/Create')
        );
})->group('admin', 'academies');


test('admin.academies.store 등록처리', function () {
    $accessId = fake()->word();
    $name = fake()->name();
    $this->actingAs($this->admin, 'admin')->post(route('admin.academies.store'), [
        //'access_id' => $accessId,
        //'password' => '1234',
        'name' => $name
    ])
        ->assertSessionHasNoErrors()
        ->assertSessionHas('message', function ($value) {
            return is_array($value) && in_array('success', $value);
        })
        ->assertRedirectToRoute('admin.academies.show', ['academy' => 1]);

    // 학원생성 체크
    $this->assertDatabaseHas('academies', [
        'name' => $name,
    ]);
    /*
    // 선생님생성 체크
    $this->assertDatabaseHas('teachers', [
        'access_id' => $accessId,
    ]);
    */
})->group('admin', 'academies');


test('admin.academies.show 상세', function () {
    $academies = Academy::factory(1)->create();
    $this->actingAs($this->admin, 'admin')->get(route('admin.academies.show', $academies[0]))
        ->assertInertia(
            fn (Assert $page) => $page
            ->component('academy/Show')
            ->has('academy')
            ->has('tags')
        );
})->group('admin', 'academies');


test('admin.academies.update 수정처리', function () {
    $originName = fake()->name();
    $changeName = fake()->name();

    $academies = Academy::factory(1)->state([
        'name' => $originName
    ])->create();

    $this->actingAs($this->admin, 'admin')->put(route('admin.academies.update', $academies[0]), [
        'name' => $changeName,
        'tags' => ['11', '222'],
        'remove_logo' => true,
    ])
        ->assertSessionHasNoErrors()
        ->assertSessionHas('message', function ($value) {
            return is_array($value) && in_array('success', $value);
        })
        ->assertRedirectToRoute('admin.academies.show', $academies[0]);

    $academy = Academy::find($academies[0]->id);
    $this->assertTrue($academy->name == $changeName);
})->group('admin', 'academies');
