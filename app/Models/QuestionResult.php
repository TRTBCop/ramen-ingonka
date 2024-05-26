<?php

namespace App\Models;

use App\Services\QuestionService;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\QuestionResult
 *
 * @property int $id
 * @property int|null $question_id
 * @property string $model_type
 * @property int $model_id
 * @property array $answers
 * @property-read \App\Models\Question $question
 *
 */
class QuestionResult extends BaseResult
{
    use HasFactory;

    protected $table = 'question_results';

    protected $fillable = [
        'student_id',
        'question_id',
        'model_id',
        'model_type',
        'answers',
    ];

    protected $casts = [
        'answers' => 'array',
    ];
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function model()
    {
        return $this->morphTo();
    }

    /**
     * userAnswer만을 배열로 받아서 문제풀이 결과 배열로 변경 후 answers 업데이트
     */
    public function setAnswers(array $userAnswers)
    {
        $question = $this->question;

        $answers = [];
        foreach ($question->answers as $answerRowIndex => $_) {
            $userAnswer = $userAnswers[$answerRowIndex];
            $answerResult = $this->createAnswerArray($answerRowIndex, $userAnswer);

            $answers[$answerRowIndex] = $answerResult;
        }
        $this->answers = $answers;
        $this->save();

        $this->evaluateCompletion();
    }

    /**
     * 문제 풀이 결과 생성
     * 끊어 읽기의 경우 문제풀이를 하나씩 풀고 채점함. 이 경우 선지형, 순서맞추기의 문제타입만 옴.
     * @param $answerRowIndex n번째 문제 풀이
     * @param $answerColIndex 복수 선지를 채점 하는 경우 answerColIndex를 받아 순서에 맞게 정답을 채점함
     */
    public function setAnswerAt(string|array $userAnswers, int $answerRowIndex = null, int $answerColIndex = null)
    {
        // 이미 문제 풀었으면 기록 안함
        if ($this->isAlreadyCompletedAnswer($answerRowIndex, $answerColIndex)) {
            return;
        };

        $answers = $this->answers;

        // 복수 선지를 나눠서 입력 하는 경우
        if (isset($answerColIndex)) {
            if (!isset($this->answers[$answerRowIndex])) {
                $answerResult = $this->createAnswerArray($answerRowIndex);
            } else {
                $answerResult = $this->answers[$answerRowIndex];
            };

            $answerResult['userAnswer'][$answerColIndex] = $userAnswers;
            $answerResult['correctAnswer'][$answerColIndex] = QuestionService::getCorrectAnswers($this->question, $answerRowIndex, $answerColIndex);
        } else {
            $answerResult = $this->createAnswerArray($answerRowIndex, $userAnswers);
        }

        $answers[$answerRowIndex] = $answerResult;

        $this->answers = $answers;

        $this->save();

        $this->evaluateCompletion();
    }

    /**
     * 완료 평가 후 로직 실행
     */
    public function evaluateCompletion(): void
    {
        if ($this->allAnswersCompleted()) {
            $this->total_answers = $this->calculateTotalAnswers();
            $this->correct_answers = $this->calculateCorrectAnswers();
            $this->correct_percent = $this->calculateCorrectPercent($this->total_answers, $this->correct_answers);
            $this->completed_at = now();
            $this->save();

            $this->model->evaluateCompletion();
        }
    }

    /**
     * 모든 문제풀이 완료 여부
     */
    public function allAnswersCompleted()
    {
        // 모든 문제를 풀었는지 체크
        $isComplete = true;
        if (isset($this->question->answers)) {
            foreach ($this->question->answers as $rowIndex => $answers) {
                foreach ($answers['answer'] as $colIndex => $answer) {
                    if (!isset($this->answers[$rowIndex]['userAnswer'][$colIndex])) {
                        $isComplete = false;
                        break;
                    }
                }
                if (!$isComplete) {
                    break;
                }
            }
        }

        return $isComplete;
    }

    /**
     * 문제풀이 결과 배열 생성
     */
    public function createAnswerArray(int $answerRowIndex, array $userAnswer = [])
    {
        $answers = $this->question->answers[$answerRowIndex];
        $type = $answers['type'];

        $action = null;
        if (isset($answers['action'])) {
            $action = $answers['action'];
        }

        $correctAnswer = $answers['answer'];


        return [
            'type' => $type,
            'action' => $action,
            'userAnswer' => $userAnswer,
            'correctAnswer' => $correctAnswer,
        ];
    }

    /**
     * 이미 해당 문제풀이를 풀었는지 여부 반환
     */
    private function isAlreadyCompletedAnswer(int $answerRowIndex, int $answerColIndex = null)
    {
        if (isset($answerColIndex)) {
            return isset($this->answers[$answerRowIndex]['userAnswer'][$answerColIndex]);
        } else {
            return isset($this->answers[$answerRowIndex]);
        }
    }

    public function calculateTotalAnswers(): int
    {
        return count($this->answers);
    }

    public function calculateCorrectAnswers(): int
    {
        $correctAnswers = 0;

        foreach ($this->answers as $answer) {
            $isCorrect = true;

            // 선지형 일 경우 배열의 순서와 상관 없이 값만 포함 하고 있으면 됨.
            if ($answer['type'] === 2) {
                foreach ($answer['userAnswer'] as $value) {
                    if (!in_array($value, $answer['correctAnswer'])) {
                        $isCorrect = false;
                    }

                    if (!$isCorrect) {
                        break;
                    }
                }
            } else {
                if ($answer['userAnswer'] !== $answer['correctAnswer']) {
                    $isCorrect = false;
                };
            }

            if ($isCorrect) {
                $correctAnswers++;
            }
        }

        return $correctAnswers;
    }
}
