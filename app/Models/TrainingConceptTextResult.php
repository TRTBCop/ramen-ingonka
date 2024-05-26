<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * App\Models\StepResult
 *
 * @property int $id
 * @property int $training_concept_text_id
 * @property boolean $is_reading_completed
 * @property-read \App\Models\trainingConceptText $trainingConceptText
 * @property-read \App\Models\StepResult $step
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StepResult> $steps
 * @property-read \App\Models\StepResult $summarizations
 * @property-read \App\Models\StepResult $reinforcements
 */
class TrainingConceptTextResult extends BaseResult
{
    use HasFactory;

    protected $table = 'training_concept_text_results';

    protected $fillable = [
        'step_result_id',
        'training_concept_text_id',
        'is_reading_completed',
    ];

    protected $casts = [
        'is_reading_completed' => 'boolean',
    ];

    protected $appends = [
        'summarizations',
        'reinforcements',
    ];


    public function trainingConceptText(): BelongsTo
    {
        return $this->belongsTo(TrainingConceptText::class);
    }

    public function step(): BelongsTo
    {
        return $this->belongsTo(StepResult::class, 'step_result_id');
    }

    public function steps(): MorphMany
    {
        return $this->morphMany(StepResult::class, 'model');
    }

    public function getSummarizationsAttribute()
    {
        return $this->steps->where('key', 'summarizations')->first();
    }

    public function getReinforcementsAttribute()
    {
        return $this->steps->where('key', 'reinforcements')->first();
    }

    public function calculateTotalAnswers(): int
    {
        return $this->steps->sum('total_answers');
    }

    public function calculateCorrectAnswers(): int
    {
        return $this->steps->sum('correct_answers');
    }

    /**
     * 완료 평가 후 로직 실행
     */
    public function evaluateCompletion(): void
    {
        $isComplete = $this->allStepsCompleted();

        // 모든 문제 다 풀었으면 채점
        if ($isComplete) {
            $this->total_answers = $this->calculateTotalAnswers();
            $this->correct_answers = $this->calculateCorrectAnswers();
            $this->correct_percent = $this->calculateCorrectPercent($this->total_answers, $this->correct_answers);
            $this->completed_at = now();
            $this->save();

            $this->step->evaluateCompletion();
        }
    }

    /**
     * 모든 단계 완료 여부
     */
    public function allStepsCompleted()
    {
        return $this->steps->whereNull('completed_at')->count() === 0;
    }
}
