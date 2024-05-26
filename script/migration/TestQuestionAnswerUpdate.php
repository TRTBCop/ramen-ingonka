<?php

namespace Script\migration;

use App\Models\Question;
use App\Models\Test;
use App\Models\Training;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 *  php artisan script migration/TestQuestionAnswerUpdate;
 *  선지형 answer가 기존에는 문자열이였는데 복수형도 고려해 배열로 변경 함.
 *
 */
class TestQuestionAnswerUpdate extends Seeder
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
                $answers =  $question->answers;

                foreach ($answers as $row => $answer) {
                    $value = $answer['answer'];

                    if (is_string($value)) {
                        $answers[$row]['answer'] = [$value];
                    }
                }

                $question->answers = $answers;
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
