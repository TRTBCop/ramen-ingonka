<?php

namespace Script\migration;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Connection;


/**
 * - image to s3
 *
 */
class S3ChangeUrl extends Seeder
{
    public function run(): void
    {
        activity()->disableLogging();

        Schema::disableForeignKeyConstraints();
        DB::disableQueryLog();

        $this->index();
        Schema::enableForeignKeyConstraints();

    }

    public function index(): void
    {
        $originTable = 'math_unit';
        $start = now();
        $this->command->line($originTable.' => '.__FUNCTION__);
        $s3BaseUrl = config('filesystems.disks.s3.url');

        $this->command->getOutput()->progressStart(1);
        $oldUrl = "https://sloop-question.s3.ap-northeast-2.amazonaws.com";

        // 문제
        DB::table('questions')
            ->update([
                'question' => DB::raw("REPLACE(question, '$oldUrl', '$s3BaseUrl')"),
                'options' => DB::raw("REPLACE(options, '$oldUrl', '$s3BaseUrl')"),
                'exam_1' => DB::raw("REPLACE(exam_1, '$oldUrl', '$s3BaseUrl')"),
                'exam_2' => DB::raw("REPLACE(exam_2, '$oldUrl', '$s3BaseUrl')"),
                'exam_3' => DB::raw("REPLACE(exam_3, '$oldUrl', '$s3BaseUrl')"),
                'exam_4' => DB::raw("REPLACE(exam_4, '$oldUrl', '$s3BaseUrl')"),
                'exam_5' => DB::raw("REPLACE(exam_5, '$oldUrl', '$s3BaseUrl')"),
            ]);



        $this->command->getOutput()->progressFinish();
        $this->done($start);
    }

    public function done($start): void
    {
        $this->command->info('Done ('.$start->diffInSeconds(now()).' seconds)'.PHP_EOL);
    }
}
