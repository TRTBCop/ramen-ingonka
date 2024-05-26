<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

/*
 * 권한 시드
 */
class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 권한
        Permission::create(['guard_name' => 'admin', 'name' => 'manager']); // 관리자관리
        Permission::create(['guard_name' => 'admin', 'name' => 'academy']); // 학원관리
        Permission::create(['guard_name' => 'admin', 'name' => 'student']); // 학생관리
        Permission::create(['guard_name' => 'admin', 'name' => 'contents']); // 컨텐츠관리
        Permission::create(['guard_name' => 'admin', 'name' => 'cs']); // cs 관리

        // admin : 슈퍼관리자
        Role::create(['guard_name' => 'admin', 'name' => 'super']);

        // admin : 운영팀
        Role::create(['guard_name' => 'admin', 'name' => 'manager'])
            ->givePermissionTo(['manager', 'academy', 'student']);

        // admin : 콘텐츠팀
        Role::create(['guard_name' => 'admin', 'name' => 'contents'])
            ->givePermissionTo(['contents']);

        // 관리자 cs팀
        Role::create(['guard_name' => 'admin', 'name' => 'cs'])
            ->givePermissionTo(['cs']);

        // 선생님 원장
        Role::create(['guard_name' => 'academy', 'name' => 'owner']);
    }
}
