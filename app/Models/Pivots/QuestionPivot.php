<?php

namespace App\Models\Pivots;

use Illuminate\Database\Eloquent\Relations\MorphPivot;

/**
 * App\Models\Pivots\QuestionPivot
 *
 * @method static \Illuminate\Database\Eloquent\Builder|QuestionPivot newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QuestionPivot newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QuestionPivot query()
 * @mixin \Eloquent
 */
class QuestionPivot extends MorphPivot
{
    protected $casts = [
        'extra' => 'array',
    ];
}