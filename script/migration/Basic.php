<?php

namespace Script\migration;

use App\Models\Curriculum;
use App\Models\Question;
use App\Models\Training;
use App\Services\MathmlService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Connection;


/**
 * todo
 * - image to s3
 *
 */
class Basic extends Seeder
{
    private Connection $mesiltong;
    protected array $sampleAcademyIds;
    protected bool $mathtype = false;
    protected MathmlService $mathmlService;


    public function run(MathmlService $mathmlService): void
    {
        activity()->disableLogging();
        $this->mathmlService = $mathmlService;

        $option = [];
        foreach (request()->server('argv') as $argv) {
            if (preg_match('/^--([^=]+)=?(.*)/', $argv, $match)) {
                if ($match[1] == 'option') {
                    $option[$match[2]] = true;
                } else {
                    $option[$match[1]] = $match[2];
                }
            }
        }

        // 개인환경 migration 에 따라 조절하여 스크립트 실행
        $works = [
            'trainings' => Training::class,
        ];

        Schema::disableForeignKeyConstraints();
        DB::disableQueryLog();

        foreach ($works as $table => $model) {
            if (isset($option['filter']) && !str_contains($table, $option['filter'])) {
                continue;
            }
            $model::truncate();
        }

        Schema::enableForeignKeyConstraints();

        $this->mesiltong = DB::connection(env('OLD_DB_DATABASE'));

        if (isset($option['sample'])) {
            $this->sampleAcademyIds = $this->mesiltong->table('ms_student')->groupBy('academy_idx')->orderByRaw('count(*) desc')->offset(100)->limit(10)->pluck('academy_idx')->toArray();
        }

        if (isset($option['mathtype'])) {
            $this->mathtype = $option['mathtype'];
        }

        foreach ($works as $table => $model) {
            if (isset($option['filter']) && !str_contains($table, $option['filter'])) {
                continue;
            }
            $this->$table($model);
        }
    }

    // 운영자 migration
    public function trainings(): void
    {
        $originTable = 'math_unit';
        $start = now();
        $this->command->line($originTable.' => '.__FUNCTION__);
        $this->command->getOutput()->progressStart($this->mesiltong->table($originTable)->count());

        $units = $this->mesiltong->table($originTable)->get();


        foreach ($units as $unit) {
            $this->command->getOutput()->progressAdvance();


            $curriculum = Curriculum::where('name', $unit->unit_name)->whereIsLeaf()->first();

            if (!$curriculum) {
                $this->command->info('not find unit ('.$unit->idx.':'.$unit->unit_name.')'.PHP_EOL);
                continue;
            }

            $oldTraining1 = $this->mesiltong->table('math_training1')->where('unit_idx', $unit->idx)->first();
            $oldTraining1Words = $this->mesiltong->table('math_training1_words')->where('unit_idx', $unit->idx)->orderBy('sort_id')->get();

            $words = [];
            foreach ($oldTraining1Words as $word) {
                $wordAddon = json_decode($word->word_addon, true);

                $image = [];
                $question = [];

                if (isset($wordAddon['question_image'])) {
                    $image['image']['src'] = $wordAddon['question_image'];
                    $image['image']['last'] = $wordAddon['is_last'];
                }

                if (isset($wordAddon['question_content'])) {
                    $question['question']['question'] = $wordAddon['question_content'];
                    $question['question']['choices'] = array_column($wordAddon['question_example'], 'value');
                    $question['question']['answer'] = $wordAddon['question_answer'];
                }

                $words[] = [
                        'word' => $word->word,
                        'type' => $word->word_type
                    ] + $image + $question;
            }
            $words = $this->removeDailykorImage($words);

            if ($oldTraining1->origin_text) {
                $oldTraining1->origin_text = $this->removeDailykorImage($oldTraining1->origin_text);
            }

            // 트레이닝 1단계
            Training::create([
                'curriculum_id' => $curriculum->id,
                'stage' => 1,
                'contents' => [
                    'text' => $this->mathtype ? $this->mathmlService->setMathmlToImage($oldTraining1->origin_text) : $oldTraining1->origin_text,
                    'words' => $this->mathtype ? $this->mathmlService->setMathmlToImage($words) : $words,
                ]
            ]);

            $oldTraining2Quiz = $this->mesiltong->table('math_training2_quiz')->where('unit_idx', $unit->idx)->orderBy('sort_id')->get();


            $questions = [];
            foreach ($oldTraining2Quiz as $quiz) {
                $question = Question::find($quiz->quiz_id);

                if ($question) {
                    $questions[] = [
                        'id' => $question->id,
                        'type' => $quiz->is_vertical == 'T' ? 1 : 0
                    ];
                }
            }

            // 트레이닝 2단계
            $training = Training::create([
                'curriculum_id' => $curriculum->id,
                'stage' => 2,
                'contents' => [
                    'questions' => $questions,
                ]
            ]);
            $training->questions()->sync(array_column($questions, 'id'));


            // 트레이닝 3-4단계
            $sheets = [];
            foreach ([3, 4] as $step) {
                $oldTraining34 = $this->mesiltong->table('math_training'.$step)->where('unit_idx', $unit->idx)->orderBy('training_no')->get();

                foreach ($oldTraining34 as $oldTraining) {
                    $oldTraining3Contents = $this->mesiltong->table('math_training'.$step.'_contents')->where('training_idx', $oldTraining->idx)->orderBy('sort_id')->get();

                    $sheet = [];
                    foreach ($oldTraining3Contents as $oldTraining3Content) {
                        $question = [];

                        $text = $oldTraining3Content->training_data;
                        $type = $oldTraining3Content->training_type;


                        if ($type == '1' && isJson($oldTraining3Content->training_data)) {
                            $trainingData = json_decode($oldTraining3Content->training_data);
                            $text = $trainingData->question_content ?? '';

                            if (isset($trainingData->question_example)) {
                                $question = [
                                    'choices' => array_column($trainingData->question_example, 'value')
                                ];

                                preg_match_all("/\[(.*?)\]/", $text, $matches);
                                if (count($matches[1]) >= 1) {
                                    $originChoices = $question['choices'];
                                    $answerChoices = $matches[1];
                                    $mergedChoices = array_merge($originChoices, $answerChoices);
                                    shuffle($mergedChoices);

                                    foreach ($answerChoices as $answerChoice) {
                                        $index = array_search($answerChoice, $mergedChoices);
                                        if ($index !== false) {
                                            $question['answer'][] = $index + 1;
                                        }
                                    }
                                    $question['choices'] = $mergedChoices;
                                }
                            }
                        }

                        $text = str_replace(['<undefined>', '</undefined>'], '', $text);

                        $sheet[] = [
                                'text' => $text,
                                'type' => $type,
                            ] + ($question ? ['question' => $question] : []);
                    }
                    $sheets[] = $sheet;
                }
            }

            $sheets = $this->removeDailykorImage($sheets);

            // 트레이닝 3-4단계
            Training::create([
                'curriculum_id' => $curriculum->id,
                'stage' => 3,
                'contents' => [
                    'sheets' => $this->mathtype ? $this->mathmlService->setMathmlToImage($sheets) : $sheets,
                ]
            ]);
        }

        $this->command->getOutput()->progressFinish();
        $this->done($start);
    }

    public function done($start): void
    {
        $this->command->info('Done ('.$start->diffInSeconds(now()).' seconds)'.PHP_EOL);
    }

    /**
     * dailykor 가 들어간 이미지 모두 제거
     * @param array|string $text
     * @return array|string
     */
    public function removeDailykorImage(array|string $text): array|string
    {
        $jsonText = null;

        if (is_array($text)) {
            $jsonText = json_encode($text);
            return json_decode(str_replace(['https:\/\/www.dailykor.com\/', 'https:\/\/dailykor.com\/'], '/', $jsonText), true);
        }else{
            return str_replace('https://dailykor.com/', '/', $text);
        }

    }
}
