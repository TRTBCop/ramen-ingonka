<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * App\Models\StepResult
 *
 * @property int $id
 * @property string $key
 * @property int $model_id
 * @property string $model_type
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TrainingConceptTextResult> $trainingConceptTexts
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\QuestionResult> $questions
 */
class StepResult extends BaseResult
{
    use HasFactory;

    protected $table = 'step_results';

    protected $fillable = [
        'key',
        'model_id',
        'model_type',
    ];

    public function trainingConceptTexts(): HasMany
    {
        return $this->hasMany(TrainingConceptTextResult::class);
    }

    public function questions(): MorphMany
    {
        return $this->morphMany(QuestionResult::class, 'model');
    }

    public function model()
    {
        return $this->morphTo();
    }


    public function calculateTotalAnswers(): int
    {
        return $this->questions->sum('total_answers') + $this->trainingConceptTexts->sum('total_answers');
    }

    public function calculateCorrectAnswers(): int
    {
        return $this->questions->sum('correct_answers') + $this->trainingConceptTexts->sum('correct_answers');
    }

    public function evaluateCompletion(): void
    {
        $isComplete = $this->allQuestionsCompleted() && $this->allTrainingConceptTextCompleted();

        // 모든 문제 다 풀었으면 채점
        if ($isComplete) {
            $this->total_answers = $this->calculateTotalAnswers();
            $this->correct_answers = $this->calculateCorrectAnswers();
            $this->correct_percent = $this->calculateCorrectPercent($this->total_answers, $this->correct_answers);
            $this->completed_at = now();
            $this->save();

            $this->model->evaluateCompletion();
        }
    }

    /**
     * 모든 문제 완료 여부
     */
    public function allQuestionsCompleted()
    {
        return $this->questions->whereNull('completed_at')->count() === 0;
    }

    /**
     * 모든 지문 완료 여부
     */
    public function allTrainingConceptTextCompleted()
    {
        return $this->trainingConceptTexts->whereNull('completed_at')->count() === 0;
    }
}
