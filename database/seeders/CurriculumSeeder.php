<?php

namespace Database\Seeders;

use App\Models\Curriculum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurriculumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $relativePath = dirname(__FILE__).'/files/curricula.sql';

        // SQL 실행
        DB::statement(file_get_contents($relativePath));
    }
}