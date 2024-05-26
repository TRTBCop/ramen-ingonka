<?php

namespace App\Models;

use App\Enums\QuestionLevelEnum;
use App\Models\Pivots\QuestionPivot;
use App\Traits\ActivityLogTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Tags\HasTags;

/**
 * App\Models\Question
 *
 * @property int $id
 * @property int|null $curriculum_id curricula_id
 * @property int $level 난이도
 * @property string|null $question 문제풀이
 * @property string $inquiry 발문
 * @property string|null $options 보기
 * @property array $answers 답안
 * @property string|null $explanation 해설
 * @property \Illuminate\Support\Carbon|null $published_at 노출일시
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $actions
 * @property-read int|null $actions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Curriculum|null $curriculum
 * @property-read mixed $curriculum_full_name
 * @property \Illuminate\Database\Eloquent\Collection<int, \Spatie\Tags\Tag> $tags
 * @property-read int|null $tags_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Test> $tests
 * @property-read int|null $tests_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Training> $trainings
 * @property-read int|null $trainings_count
 * @method static \Database\Factories\QuestionFactory factory($count = null, $state = [])
 * @method static Builder|Question listFilter()
 * @method static Builder|Question newModelQuery()
 * @method static Builder|Question newQuery()
 * @method static Builder|Question onlyTrashed()
 * @method static Builder|Question query()
 * @method static Builder|Question whereAnswers($value)
 * @method static Builder|Question whereCreatedAt($value)
 * @method static Builder|Question whereCurriculumId($value)
 * @method static Builder|Question whereDeletedAt($value)
 * @method static Builder|Question whereExplanation($value)
 * @method static Builder|Question whereId($value)
 * @method static Builder|Question whereInquiry($value)
 * @method static Builder|Question whereLevel($value)
 * @method static Builder|Question whereOptions($value)
 * @method static Builder|Question wherePublishedAt($value)
 * @method static Builder|Question whereQuestion($value)
 * @method static Builder|Question whereUpdatedAt($value)
 * @method static Builder|Question withAllTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static Builder|Question withAllTagsOfAnyType($tags)
 * @method static Builder|Question withAnyTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static Builder|Question withAnyTagsOfAnyType($tags)
 * @method static Builder|Question withTrashed()
 * @method static Builder|Question withoutTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static Builder|Question withoutTrashed()
 * @mixin \Eloquent
 */
class Question extends Model
{
    use HasFactory;
    use ActivityLogTrait;
    use HasTags;
    use SoftDeletes;

    protected $casts = [
        'answers' => 'array',
        'published_at' => 'datetime',
    ];

    protected $fillable = [
        'curriculum_id',
        'level', // 난이도
        'question', // 풀이과정
        'inquiry', // 문제
        'options', // 보기
        'answers', // 답안
        'explanation', // 해설
        'published_at',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'curriculum_full_name',
        'txt_level',
    ];

    protected $attributes = [
        'question' => '',
        'options' => '',
        'answers' => '[]',
        'explanation' => '',
    ];

    public function trainings(): MorphToMany
    {
        return $this->morphedByMany(Training::class, 'model', 'questionables')
            ->using(QuestionPivot::class);
    }

    public function tests(): MorphToMany
    {
        return $this->morphedByMany(Test::class, 'model', 'questionables')
            ->using(QuestionPivot::class);
    }


    public function curriculum(): BelongsTo
    {
        return $this->belongsTo(Curriculum::class);
    }

    public function getCurriculumFullNameAttribute()
    {
        // curriculum 관계가 로드되어 있는지 확인
        if ($this->relationLoaded('curriculum') && $this->curriculum) {
            // tags 관계가 로드되어 있는지 확인
            if ($this->curriculum->relationLoaded('ancestors')) {
                return $this->curriculum->ancestors->pluck('name')->join(' > ');
            }
        }

        return null; // 관계가 로드되지 않은 경우 null 반환
    }


    /**
     *
     * 목록 검색 필터
     * @alias listFilter
     * @param Builder $query
     * @return Builder
     */
    public function scopeListFilter(Builder $query): Builder
    {
        $query->orderBy('id', 'desc');

        if (isset(request()->filters) && is_array(request()->filters)) {
            foreach (request()->filters as $column => $value) {
                switch ($column) {
                    case 'tags': // 태그
                        if (is_array($value)) {
                            $query->withAnyTags($value, 'questions');
                        }
                        break;
                    case 'type': // 결제수단
                        if (is_array($value)) {
                            $query->whereIn($column, $value);
                        } else {
                            $query->where($column, $value);
                        }
                        break;
                }
            }
        }

        // 키워드 검색
        /*
        if (isset(request()->filter_text) && request()->filter_text) {
            $query->where(function ($query) {
                $columns = ['title'];
                foreach ($columns as $column) {
                    $query->orWhere($column, 'like', '%'.trim(request()->filter_text).'%');
                }
            });
        }
        */

        return $query;
    }

    public function getTxtLevelAttribute(): string
    {
        return QuestionLevelEnum::options()[$this->level];
    }
}
