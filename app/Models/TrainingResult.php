<?php

namespace App\Models;

use App\Traits\ActivityLogTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * App\Models\TrainingResult
 *
 * @property int $id
 * @property int $curriculum_id curriculum.id
 * @property int $training_id trainings.id
 * @property int $timer 소요 시간
 * @property int $round n번째 학습
 * @property int $score 점수
 * @property array|null $extra 결과 데이터
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $actions
 * @property-read int|null $actions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Training $training
 * @property-read \App\Models\Curriculum $curriculum
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StepResult> $steps
 */
class TrainingResult extends BaseResult
{
    use HasFactory;
    use ActivityLogTrait;

    protected $fillable = [
        'curriculum_id',
        'training_id',
        'timer',
        'round',
        'extra',
        'score',
    ];

    protected $casts = [
        'extra' => 'array',
    ];

    public function training(): BelongsTo
    {
        return $this->BelongsTo(Training::class);
    }

    public function curriculum(): BelongsTo
    {
        return $this->BelongsTo(Curriculum::class);
    }

    public function steps(): MorphMany
    {
        return $this->morphMany(StepResult::class, 'model');
    }

    public function stepByKey(string|int $key)
    {
        return $this->steps->where('key', $key)->first();
    }

    public function calculateTotalAnswers(): int
    {
        return $this->steps->sum('total_answers');
    }

    public function calculateCorrectAnswers(): int
    {
        return $this->steps->sum('correct_answers');
    }

    public function calculateScore(int $correctPercent): int
    {
        return $this->timer > 3600 ? max(0, $correctPercent - 10) : $correctPercent;
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
            $this->score = $this->calculateScore($this->correct_percent);
            $this->completed_at = now();
            $this->save();
        }
    }

    /**
     * 모든 단계 완료 여부
     */
    public function allStepsCompleted()
    {
        return $this->steps->whereNull('completed_at')->count() === 0;
        ;
    }

    /**
     *
     * 목록 검색 필터
     * @alias listFilter
     * @param $query
     * @return Builder
     */
    public function scopeListFilter($query): Builder
    {
        $user = auth()->user();

        if (isset($user) && $user instanceof Student) {
            $query->where(['student_id' => $user->id]);
        }

        $query->with(['training', 'curriculum.ancestors'])->orderByDesc('completed_at');

        if (isset(request()->filters) && is_array(request()->filters)) {
            foreach (request()->filters as $column => $value) {
                switch ($column) {
                    case 'is_completed':  // 완료 유무
                        $query->whereNotNull('completed_at');
                        break;
                    case 'stage': // 훈련 스테이지
                        if (is_array($value)) {
                            $query->whereHas('training', function ($query) use ($value) {
                                $query->whereIn('stage', $value);
                            });
                        } else {
                            $query->whereHas('training', function ($query) use ($value) {
                                $query->where('stage', $value);
                            });
                        }
                        break;
                    case 'parent_curriculum_id':
                        $query->whereHas('curriculum.ancestors', function ($query) use ($value) {
                            $query->where('id', $value);
                        });

                        break;
                }
            }
        }

        // 키워드 검색
        if (isset(request()->filter_text) && request()->filter_text) {
            $query->where(function ($query) {
                $columns = ['title', 'contents'];
                foreach ($columns as $column) {
                    $query->orWhere($column, 'like', '%'.trim(request()->filter_text).'%');
                }
            });
        }

        return $query;
    }
}
