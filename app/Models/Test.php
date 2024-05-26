<?php

namespace App\Models;

use App\Traits\ActivityLogTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * App\Models\Test
 *
 * @property int $id
 * @property string $title 학년/학기
 * @property array|null $contents
 * @property \Illuminate\Support\Carbon|null $published_at 노출일시
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $actions
 * @property-read int|null $actions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Question> $questions
 * @property-read \App\Models\TestResult|null $result
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TestResult> $results
 * @property-read int|null $results_count
 * @method static \Database\Factories\TestFactory factory($count = null, $state = [])
 * @method static Builder|Test listFilter()
 * @method static Builder|Test newModelQuery()
 * @method static Builder|Test newQuery()
 * @method static Builder|Test query()
 * @method static Builder|Test whereContents($value)
 * @method static Builder|Test whereCreatedAt($value)
 * @method static Builder|Test whereId($value)
 * @method static Builder|Test wherePublishedAt($value)
 * @method static Builder|Test whereTitle($value)
 * @method static Builder|Test whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Test extends Model
{
    use HasFactory;
    use ActivityLogTrait;

    protected $casts = [
        'published_at' => 'datetime',
        'contents' => 'array'
    ];

    protected $fillable = [
        'title',
        'contents',
        'published_at',
    ];

    public function questions(): MorphToMany
    {
        $questionIds = [];
        if ($this->contents !== null && isset($this->contents['questions'])) {
            foreach ($this->contents['questions'] as $item) {
                $questionIds[] = $item['id'];
            }
        }

        return $this->morphToMany(Question::class, 'model', 'questionables')
                    ->whereIn('questions.id', $questionIds)
                    ->with('curriculum')
                    ->orderByRaw('FIELD(questions.id, '.implode(',', $questionIds).')');
    }

    public function results(): HasMany
    {
        return $this->HasMany(TestResult::class);
    }

    public function result(): HasOne
    {
        return $this->hasOne(TestResult::class);
    }

    /*
    protected function serializeDate(\DateTimeInterface $date): string
    {
        return $date->format("Y-m-d H:i:s");
    }
    */

    /**
     *
     * 목록 검색 필터
     * @alias listFilter
     * @param Builder $query
     * @return Builder
     */
    public function scopeListFilter(Builder $query): Builder
    {
        $query->orderBy('id', 'asc');

        if (isset(request()->filters) && is_array(request()->filters)) {
            foreach (request()->filters as $column => $value) {
                switch ($column) {
                    case 'published_at':
                        if ($value) {
                            $query->whereNotNull('published_at');
                        } else {
                            $query->whereNull('published_at');
                        }
                        break;
                }
            }
        }

        // 키워드 검색
        if (isset(request()->filter_text) && request()->filter_text) {
            $query->where(function ($query) {
                $columns = ['title'];
                foreach ($columns as $column) {
                    $query->orWhere($column, 'like', '%'.trim(request()->filter_text).'%');
                }
            });
        }

        return $query;
    }
}
