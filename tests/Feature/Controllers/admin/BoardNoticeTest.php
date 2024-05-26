<?php

namespace Admin;

use App\Models\BoardNotice;
use App\Models\Admin;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class)->beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
    $this->admin = Admin::factory()->create()->assignRole('super');
    Storage::fake('dailykor-laravel-media');
});


test('admin.board-notices.index 공지사항 목록', function () {
    BoardNotice::factory(10)->create();
    BoardNotice::factory(10)->state([
        'scope' => 4,
        'title' => '1234',
    ])->create();
    BoardNotice::factory(10)->state([
        'scope' => 1,
        'title' => '1234',
    ])->create();

    $this->actingAs($this->admin, 'admin')->get(route('admin.board-notices.index', [
        'filters[scope]' => [
            1,
            2,
        ],
        'filter_text' => 1234,
    ]))
        ->assertInertia(
            fn (Assert $page) => $page
                ->component('board-notices/Index')
                ->where('collection.meta.total', 10)
                ->has('page')
        );
})->group('admin', 'board-notices');


test('admin.board-notices.show 공지사항 상세', function () {
    $boardNotice = BoardNotice::factory()->create();

    $this->actingAs($this->admin, 'admin')->get(route('admin.board-notices.show', $boardNotice))
        ->assertInertia(
            fn (Assert $page) => $page
                ->component('board-notices/Show')
                ->has('board')
                ->has('page')
        );
})->group('admin', 'board-notices');


test('admin.board-notices.store 공지사항 등록', function () {
    $this->actingAs($this->admin, 'admin')->post(route('admin.board-notices.store'), [
        'scope' => [1, 2, 4],
        'title' => '1234',
        'contents' => fake()->text(),
        'files' => [
            UploadedFile::fake()->image('file1.jpg'),
            UploadedFile::fake()->create('file2.hwp')
        ]
    ])
        ->assertSessionHasNoErrors()
        ->assertSessionHas('message', function ($value) {
            return is_array($value) && in_array('success', $value);
        })
        ->assertRedirect(route('admin.board-notices.show', 1));

    // 생성 체크
    $this->assertDatabaseHas(BoardNotice::class, [
        'title' => 1234,
    ]);
})->group('admin', 'board-notices');

test('admin.board-notices.update 공지사항 수정', function () {
    $boardNotice = BoardNotice::factory()->create();
    $boardNotice->addMedia(UploadedFile::fake()->create('file1.hwp'))->toMediaCollection('file');

    $this->actingAs($this->admin, 'admin')->put(route('admin.board-notices.update', $boardNotice), [
        'title' => '1234',
        'contents' => '1234',
        'is_published' => true,
        'del_files' => [
            $boardNotice->files[0]['id']
        ],
        'files' => [
            UploadedFile::fake()->image('file2.jpg'),
            UploadedFile::fake()->create('file3.hwp')
        ]
    ])
        ->assertSessionHasNoErrors()
        ->assertSessionHas('message', function ($value) {
            return is_array($value) && in_array('success', $value);
        })
        ->assertRedirect(route('admin.board-notices.show', $boardNotice));

    $boardNotice->refresh();
    $this->assertTrue(!is_null($boardNotice->published_at));
    $this->assertSame(count($boardNotice->files), 2);
    $this->assertSame($boardNotice->title, '1234');
})->group('admin', 'board-notices');


test('admin.board-notices.destroy 공지사항 삭제', function () {
    $boardNotice = BoardNotice::factory()->create();

    $this->actingAs($this->admin, 'admin')->delete(route('admin.board-notices.destroy', $boardNotice))
        ->assertSessionHasNoErrors()
        ->assertSessionHas('message', function ($value) {
            return is_array($value) && in_array('success', $value);
        })
        ->assertRedirect(route('admin.board-notices.index'));

    // soft deleted check
    $this->assertDatabaseMissing(BoardNotice::class, [
        'id' => $boardNotice->id,
        'deleted_at' => null
    ]);
})->group('admin', 'board-notices');
