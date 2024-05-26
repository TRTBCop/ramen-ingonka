<?php

namespace App\Services;

use App\Models\Question;
use Ramsey\Uuid\Uuid;
use App\Services\MathmlService;

class QuestionService
{
    /**
     */
    public function __construct()
    {
    }

    /**
     * 정답을 모두 스트링으로
     * @param $answer
     * @return array|string
     */
    public static function arrayValuesToString($answer): array|string
    {
        if (is_array($answer)) {
            $result = [];
            foreach ($answer as $key => $item) {
                if (is_array($item)) {
                    $result[$key] = self::arrayValuesToString($item); // 재귀 호출로 다차원 배열 처리
                } else {
                    $result[$key] = (string)$item; // 값을 문자열로 변환
                }
            }
        } else {
            $result = (string)$answer;
        }

        return $result;
    }

    /**
     * 정답을 모두 빈문자열로
     * @param $answer
     * @return array|string
     */
    public static function arrayValuesToSpace($answer): array|string
    {
        if (is_array($answer)) {
            $result = [];
            foreach ($answer as $key => $item) {
                if (is_array($item)) {
                    $result[$key] = self::arrayValuesToSpace($item); // 재귀 호출로 다차원 배열 처리
                } else {
                    $result[$key] = '';
                }
            }
        } else {
            $result = '';
        }

        return $result;
    }

    /**
     * 문제 배열을 받아 학습에 사용할 수 있는 형태로 변경
     * @param $questions
     * @return array
     */
    public static function questionToAppQuestion($question): array
    {
        $result = $question;

        $answers =  $question['answers'];
        foreach ($answers as $j => $value) {
            if (isset($answers[$j]['choices'])) {
                $answers[$j]['choices'] = array_map(function ($value, $index) {
                    return ['id' => $index + 1, 'choice' => $value];
                }, $answers[$j]['choices'], array_keys($answers[$j]['choices']));
            }

            // 보기 섞어서 삽입
            if ($value['type'] === 3) {
                shuffle($answers[$j]['choices']);
            }

            if ($value['answer']) {
                $answers[$j]['answer'] = self::arrayValuesToSpace($value['answer']);
            }
        }
        $result['answers'] = $answers;

        $result = self::questionToMathmlToImage($result);

        // 필요 없는 데이터 제외
        unset($result['curriculum_id']);
        unset($result['pivot']);
        unset($result['level']);
        unset($result['published_at']);
        unset($result['created_at']);
        unset($result['updated_at']);
        unset($result['deleted_at']);
        unset($result['curriculum_full_name']);


        return $result;
    }

    /**
     * 문제 내에 있는 수식을 svg로 변경
     * @param $question
     * @return array
     */
    public static function questionToMathmlToImage(array $question): array
    {
        $result = $question;
        $mathmlService = new MathmlService();

        $result['question'] = $mathmlService->setMathmlToImage($result['question']);
        $result['inquiry'] = $mathmlService->setMathmlToImage($result['inquiry']);
        $result['options'] = $mathmlService->setMathmlToImage($result['options']);
        $result['answers'] = $mathmlService->setMathmlToImage($result['answers']);
        $result['explanation'] = $mathmlService->setMathmlToImage($result['explanation']);


        return $result;
    }

    /** 위치에 맞는 정답을 체크함 */
    public static function verifyQuestionAnswer(Question $question, array|string $answer, int $row, int $col = null)
    {
        return self::getCorrectAnswers($question, $row, $col) === $answer;
    }

    /** 정답 입력 배열에서 정답이 아닌거는 빈 문자열로 치환 */
    public static function getFilterCorrectAnswers(Question $question, array $answer, int $row)
    {
        $result = [];
        foreach (self::getCorrectAnswers($question, $row) as $key => $correctValue) {
            $result[$key] = $correctValue === $answer[$key] ? $correctValue : '';
        }

        return $result;
    }

    /** 위치에 맞는 정답 값을 가져옴 */
    public static function getCorrectAnswers(Question $question, int $row, int $col = null)
    {
        return isset($col) ? $question->answers[$row]['answer'][$col] : $question->answers[$row]['answer'];
    }

    public function update(Question $question, array $input): Question
    {
        $messageType = empty($question->id) ? '등록' : '수정';


        foreach ($input['answers'] as &$answer) {
            // 고유 식별번호를 넣어줌
            if (empty($answer['uuid'])) {
                $answer['uuid'] = Uuid::uuid4()->toString();
            }
        }
        unset($answer);

        $input['is_published'] = $input['is_published'] ?? false;
        if (!$question->published_at && $input['is_published']) {
            $input['published_at'] = now();
        } elseif ($question->published_at && !$input['is_published']) {
            $input['published_at'] = null;
        }
        $question->fill($input)->setActivitylogOptions([
            'description' => '문제가 '.$messageType.'되었습니다.',
            'is_show' => 1,
        ])->save();

        // tags
        if (isset($input['tags'])) {
            if (!$input['tags']) {
                $question->tags()->detach();
            }

            foreach ($input['tags'] as $tagType => $tags) {
                $question->syncTagsWithType($tags, $tagType);
            }
        }

        // 임시 이미지 업로드
        $editorColumns = ['question', 'inquiry', 'options', 'answers', 'explanation'];

        foreach ($editorColumns as $value) {
            if (isset($input[$value])) {
                $question->{$value} = fileTempMove($input[$value], $question->{$value}, 'questions/'.$question->id);
            }
        }

        if ($question->isDirty()) {
            $question->save();
        }
        $question->refresh();

        // 연관 테이블이 있는경우
        if (isset($input['rel']['table']) && method_exists($question, $input['rel']['table'])) {
            $table = $input['rel']['table'];
            $question->$table()->sync([
                $input['rel']['id'] => [
                    'extra' => ($input['rel']['extra'] ?? [])
                ]
            ], true);
        }


        return $question;
    }
}
