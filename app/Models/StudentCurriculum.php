<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\StudentCurriculum
 *
 * @property-read \App\Models\Curriculum $curriculum
 * @property-read \App\Models\Student|null $student
 * @method static \Database\Factories\StudentCurriculumFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|StudentCurriculum newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentCurriculum newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentCurriculum query()
 * @mixin \Eloquent
 */
class StudentCurriculum extends Model
{
    use HasFactory;

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function curriculum(): BelongsTo
    {
        return $this->belongsTo(Curriculum::class);
    }
}
