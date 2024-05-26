<?php

namespace App\Models;

use App\Enums\CurriculumElementEnum;
use App\Traits\ActivityLogTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Kalnoy\Nestedset\NodeTrait;

/**
 * App\Models\Curriculum
 *
 * @property int $id
 * @property CurriculumElementEnum $element 속성
 * @property string $name 이름
 * @property string $type
 * @property int $_lft
 * @property int $_rgt
 * @property int|null $parent_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $actions
 * @property-read int|null $actions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Kalnoy\Nestedset\Collection<int, Curriculum> $children
 * @property-read int|null $children_count
 * @property-read string $parent_names
 * @property-read string $txt_element
 * @property-read Curriculum|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Student> $students
 * @property-read int|null $students_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TrainingResult> $training_results
 * @property-read int|null $training_results_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Training> $trainings
 * @property-read int|null $trainings_count
 * @method static \Kalnoy\Nestedset\Collection<int, static> all($columns = ['*'])
 * @method static \Kalnoy\Nestedset\QueryBuilder|Curriculum d()
 * @method static \Database\Factories\CurriculumFactory factory($count = null, $state = [])
 * @method static \Kalnoy\Nestedset\Collection<int, static> get($columns = ['*'])
 * @method static \Kalnoy\Nestedset\QueryBuilder|Curriculum listFilter()
 * @method static \Kalnoy\Nestedset\QueryBuilder|Curriculum newModelQuery()
 * @method static \Kalnoy\Nestedset\QueryBuilder|Curriculum newQuery()
 * @method static \Kalnoy\Nestedset\QueryBuilder|Curriculum query()
 * @method static Builder|Curriculum whereCreatedAt($value)
 * @method static Builder|Curriculum whereElement($value)
 * @method static Builder|Curriculum whereId($value)
 * @method static Builder|Curriculum whereLft($value)
 * @method static Builder|Curriculum whereName($value)
 * @method static Builder|Curriculum whereParentId($value)
 * @method static Builder|Curriculum whereRgt($value)
 * @method static Builder|Curriculum whereType($value)
 * @method static Builder|Curriculum whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Curriculum extends Model
{
    use HasFactory;
    use NodeTrait;
    use ActivityLogTrait;

    public const MATH_ROOT_ID = 1; // 수학 root id

    protected $casts = [
        'element' => CurriculumElementEnum::class,
    ];

    protected $fillable = [
        'name',
        'element' // 속성
    ];

    protected $hidden = [
    ];
    protected $appends = [
        'txt_element'
    ];


    public function trainings(): HasMany
    {
        return $this->hasMany(Training::class);
    }

    public function students(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'student_curriculum');
    }

    public function training_results(): HasMany
    {
        return $this->hasMany(TrainingResult::class);
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
        $query->defaultOrder();

        $categoryId = null;
        if (isset(request()->filters['category_depth_1_id'])) {
            $categoryId = request()->filters['category_depth_1_id'];
        }

        if (isset(request()->filters['category_depth_2_id'])) {
            $categoryId = request()->filters['category_depth_2_id'];
        }

        if ($categoryId) {
            $query->descendantsOf($categoryId);
        }

        // 키워드 검색
        if (isset(request()->filter_text) && request()->filter_text) {
            $query->where(function ($query) {
                $columns = ['name'];
                foreach ($columns as $column) {
                    $query->orWhere($column, 'like', '%'.trim(request()->filter_text).'%');
                }
            });
        }

        return $query;
    }

    public function getTxtElementAttribute(): string
    {
        return $this->element ? $this->element->text() : '';
    }


    public function getParentNamesAttribute(): string
    {
        return $this->ancestors ? implode('>', $this->ancestors->pluck('name')->toArray()) : '';
    }
}
