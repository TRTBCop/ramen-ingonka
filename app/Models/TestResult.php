<?php

namespace App\Models;

use App\Traits\ActivityLogTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\TestResult
 *
 * @property int $id
 * @property string $uuid
 * @property int $test_id tests.id
 * @property int $student_id students.id
 * @property int $point 점수
 * @property int $timer 소요시간
 * @property array|null $extra 결과데이터
 * @property \Illuminate\Support\Carbon|null $completed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $actions
 * @property-read int|null $actions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Student $student
 * @property-read \App\Models\Test $test
 * @method static \Database\Factories\TestResultFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|TestResult newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TestResult newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TestResult query()
 * @method static \Illuminate\Database\Eloquent\Builder|TestResult whereCompletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TestResult whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TestResult whereExtra($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TestResult whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TestResult wherePoint($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TestResult whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TestResult whereTestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TestResult whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TestResult whereUuid($value)
 * @mixin \Eloquent
 */
class TestResult extends Model
{
    use HasFactory;
    use ActivityLogTrait;

    protected $fillable = [
        'uuid',
        'student_id',
        'test_id',
        'point',
        'timer',
        'extra',
        'completed_at'
    ];

    protected $casts = [
        'extra' => 'array',
        'completed_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class);
    }
}
