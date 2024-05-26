<?php

namespace App\Models;

use App\Traits\ActivityLogTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\TrainingConceptSet
 *
 * @property int $id
 * @property int $training_id trainings.id
 * @property array|null $readings 학습컨텐츠-읽기 JSON
 * @property array|null $summarizations 학습컨텐츠-요약 JSON
 * @property array|null $reinforcements 학습컨텐츠-다지기 JSON
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $actions
 * @property-read int|null $actions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Training $training
 * @method static \Database\Factories\TrainingConceptTextFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingConceptText newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingConceptText newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingConceptText query()
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingConceptText whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingConceptText whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingConceptText whereReadings($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingConceptText whereReinforcements($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingConceptText whereSummarizations($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingConceptText whereTrainingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingConceptText whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TrainingConceptText extends Model
{
    use HasFactory, ActivityLogTrait;

    protected $casts = [
        'readings' => 'array',
        'summarizations' => 'array',
        'reinforcements' => 'array',
    ];

    protected $fillable = [
        'training_id',
        'readings',
        'summarizations',
        'reinforcements',
    ];


    public function training(): BelongsTo
    {
        return $this->belongsTo(Training::class);
    }
}
