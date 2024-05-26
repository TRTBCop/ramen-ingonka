<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Test;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 관리자 등록
        Admin::create([
            'name' => '최고관리자',
            'access_id' => 'admin',
            'password' => bcrypt('1234'),
        ])->assignRole('super');

        // 학생 1000번 부터 시작
        DB::statement('ALTER TABLE students AUTO_INCREMENT = 1000;');
        // 학원 1000번 부터 시작
        DB::statement('ALTER TABLE academies AUTO_INCREMENT = 1000;');


        // 진단평가 등록
        $titles = [
            '초등 3학년 1학기',
            '초등 3학년 2학기',
            '초등 4학년 1학기',
            '초등 4학년 2학기',
            '초등 5학년 2학기',
            '초등 5학년 1학기',
            '초등 6학년 2학기',
            '초등 6학년 1학기',
            '중등 1학년 1학기',
            '중등 1학년 2학기',
            '중등 2학년 1학기',
            '중등 2학년 2학기',
            '중등 3학년 1학기',
            '중등 3학년 2학기',
        ];

        foreach ($titles as $title) {
            Test::create([
                'title' => $title,
            ]);
        }
    }
}
