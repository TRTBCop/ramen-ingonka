<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $student_id students.id
 * @property \Illuminate\Support\Carbon|null $completed_at 완료일시
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $total_answers 전체 문제 풀이 수
 * @property int $correct_answers 전체 정답 문제 풀이 수
 * @property int $correct_percent 정답률
 * @property-read \App\Models\Student $student
 *
 * @method static Builder|BaseResult listFilter()
 * @method static Builder|BaseResult newModelQuery()
 * @method static Builder|BaseResult newQuery()
 * @method static Builder|BaseResult query()
 * @method static Builder|BaseResult whereCompletedAt($value)
 * @method static Builder|BaseResult whereCreatedAt($value)
 * @method static Builder|BaseResult whereCurriculumId($value)
 * @method static Builder|BaseResult whereExtra($value)
 * @method static Builder|BaseResult whereId($value)
 * @method static Builder|BaseResult wherePoint($value)
 * @method static Builder|BaseResult whereStage($value)
 * @method static Builder|BaseResult whereStudentId($value)
 * @method static Builder|BaseResult whereTotalCorrect($value)
 * @method static Builder|BaseResult whereTotalQuestions($value)
 * @method static Builder|BaseResult whereTrainingId($value)
 * @method static Builder|BaseResult whereUpdatedAt($value)
 */
abstract class BaseResult extends Model
{
    protected $baseFillable = [
        'student_id',
        'total_answers',
        'correct_answers',
        'correct_percent',
        'completed_at',
    ];

    protected $baseCasts = [
        'completed_at' => 'datetime',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->mergeBaseFillable();
        $this->mergeBaseCasts();
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    abstract public function calculateTotalAnswers(): int;

    abstract public function calculateCorrectAnswers(): int;

    public function calculateCorrectPercent(int $totalAnswers, int $correctAnswers): int
    {
        if ($totalAnswers <= 0) {
            return 0;
        }

        return round(($correctAnswers / $totalAnswers) * 100);
    }

    /**
     * 완료 평가 후 완료 처리
     */
    abstract public function evaluateCompletion(): void;

    protected function mergeBaseFillable(): void
    {
        $this->fillable = array_merge($this->baseFillable, $this->fillable);
    }

    protected function mergeBaseCasts(): void
    {
        $this->casts = array_merge($this->baseCasts, $this->casts);
    }
}
