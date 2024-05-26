<?php

namespace App\Models;

use App\Models\Pivots\QuestionPivot;
use App\Traits\ActivityLogTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * App\Models\Training
 *
 * @property int $id
 * @property int $curriculum_id curricula.id
 * @property int $stage 학습단계
 * @property array|null $contents 학습컨텐츠 Json
 * @property string|null $published_at 노출일시
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $actions
 * @property-read int|null $actions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Curriculum $curriculum
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Question> $questions
 * @property-read int|null $questions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TrainingConceptText> $training_concept_texts
 * @property-read int|null $training_concept_texts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TrainingResult> $results
 * @method static \Database\Factories\TrainingFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Training newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Training newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Training query()
 * @method static \Illuminate\Database\Eloquent\Builder|Training whereContents($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Training whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Training whereCurriculumId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Training whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Training wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Training whereStage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Training whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Training extends Model
{
    use HasFactory;
    use ActivityLogTrait;

    protected $casts = [
        'contents' => 'array',
    ];

    protected $fillable = [
        'curriculum_id',
        'stage',
        'contents',
        'published_at'
    ];

    public function questions(): MorphToMany
    {
        return $this->morphToMany(Question::class, 'model', 'questionables')
             ->using(QuestionPivot::class);
    }

    public function curriculum(): BelongsTo
    {
        return $this->belongsTo(Curriculum::class);
    }

    public function results(): HasMany
    {
        return $this->HasMany(TrainingResult::class);
    }

    /*
     * 개념훈련 지문
     */
    public function training_concept_texts(): HasMany
    {
        return $this->hasMany(TrainingConceptText::class);
    }
}
