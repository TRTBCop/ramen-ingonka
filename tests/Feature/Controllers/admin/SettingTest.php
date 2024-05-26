<?php

namespace Admin;

use App\Models\Admin;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class)->beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
    $this->admin = Admin::factory()->create()->assignRole('super');
});


test('admin.setting.policy.show 이용약관관리 form', function () {
    $this->actingAs($this->admin, 'admin')->get(route('admin.settings.policy.show'))
        ->assertInertia(
            fn (Assert $page) => $page
            ->component('settings/policy/Show')
            ->has('agree')
            ->has('privacy')
            ->has('marketing')
            ->has('page')
        );
})->group('admin', 'settings');


test('admin.setting.policy.update 이용약관관리 저장', function () {
    $this->actingAs($this->admin, 'admin')->put(route('admin.settings.policy.update'), [
        'agree' => '1234',
        'privacy' => '5678',
        'marketing' => '910',
    ])
        ->assertSessionHasNoErrors()
        ->assertSessionHas('message', function ($value) {
            return is_array($value) && in_array('success', $value);
        })
        ->assertRedirect(route('admin.settings.policy.show'));

    $this->assertSame(setting('agree'), '1234');
    $this->assertSame(setting('privacy'), '5678');
    $this->assertSame(setting('marketing'), '910');
})->group('admin', 'settings');
