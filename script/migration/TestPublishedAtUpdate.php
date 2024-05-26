<?php

namespace Script\migration;

use App\Models\Question;
use App\Models\Test;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 *  php artisan script migration/TestPublishedAtUpdate;
 *  진단평가 모든 문제 검수 완료 처리
 *
 */
class TestPublishedAtUpdate extends Seeder
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
        $start = now();

        $tests = Test::all();

        foreach ($tests as $test) {
            // 출제중인 문제목록
            $questions = $test->questions();

            $this->command->getOutput()->progressStart($questions->count());

            $questions->each(function (Question $question) {
                $question->published_at = now();
                $question->save();
                $this->command->getOutput()->progressAdvance();
            });
        }

        $this->command->getOutput()->progressFinish();
        $this->done($start);
    }

    public function done($start): void
    {
        $this->command->info('Done ('.$start->diffInSeconds(now()).' seconds)'.PHP_EOL);
    }
}
