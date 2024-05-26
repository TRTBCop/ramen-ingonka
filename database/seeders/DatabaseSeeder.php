<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            InitSeeder::class, // 기본 등록
            SettingSeeder::class, // 약관
            CurriculumSeeder::class, // 커리큘럼
        ]);
    }
}
