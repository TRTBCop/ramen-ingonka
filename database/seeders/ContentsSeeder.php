<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * php artisan db:seed --class=ContentsSeeder
 */
class ContentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $start = now();

        $fileNames = ['questions', 'trainings', 'training_concept_texts', 'tests', 'questionables'];

        $this->command->getOutput()->progressStart(count($fileNames));

        foreach ($fileNames as $fileName) {
            $relativePath = dirname(__FILE__).'/files/'.$fileName.'.sql';

            // SQL 실행
            DB::unprepared(file_get_contents($relativePath));
            $this->command->getOutput()->progressAdvance();
        }

        $this->command->getOutput()->progressFinish();
        $this->command->info('Done ('.$start->diffInSeconds(now()).' seconds)'.PHP_EOL);
    }
}
