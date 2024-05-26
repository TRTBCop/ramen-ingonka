<?php

namespace App\Models;

use App\Traits\ActivityLogTrait;
use App\Traits\PaginatableTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * App\Models\BoardNotice
 *
 * @property int $id
 * @property int $admin_id admins.id
 * @property int $scope 노출범위(비트연산)
 * @property string $category 분류
 * @property string $sub_category 세부분류
 * @property string $title 제목
 * @property string $contents 내용
 * @property \Illuminate\Support\Carbon|null $published_at 노출일시
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at 삭제일시
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $actions
 * @property-read int|null $actions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Admin $admin
 * @property-read array $files
 * @property-read array $txt_scope
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @method static \Database\Factories\BoardNoticeFactory factory($count = null, $state = [])
 * @method static Builder|BoardNotice listFilter()
 * @method static Builder|BoardNotice newModelQuery()
 * @method static Builder|BoardNotice newQuery()
 * @method static Builder|BoardNotice onlyTrashed()
 * @method static Builder|BoardNotice query()
 * @method static Builder|BoardNotice whereAdminId($value)
 * @method static Builder|BoardNotice whereCategory($value)
 * @method static Builder|BoardNotice whereContents($value)
 * @method static Builder|BoardNotice whereCreatedAt($value)
 * @method static Builder|BoardNotice whereDeletedAt($value)
 * @method static Builder|BoardNotice whereDisplayHome($value)
 * @method static Builder|BoardNotice whereDisplayHomeEndDate($value)
 * @method static Builder|BoardNotice whereDisplayHomeStartDate($value)
 * @method static Builder|BoardNotice whereDisplayHomeTitle($value)
 * @method static Builder|BoardNotice whereId($value)
 * @method static Builder|BoardNotice wherePublishedAt($value)
 * @method static Builder|BoardNotice whereScope($value)
 * @method static Builder|BoardNotice whereSubCategory($value)
 * @method static Builder|BoardNotice whereTitle($value)
 * @method static Builder|BoardNotice whereUpdatedAt($value)
 * @method static Builder|BoardNotice withTrashed()
 * @method static Builder|BoardNotice withoutTrashed()
 * @mixin \Eloquent
 */
class BoardNotice extends Model implements HasMedia
{
    use HasFactory;
    use ActivityLogTrait;
    use SoftDeletes;
    use InteractsWithMedia;
    use PaginatableTrait;

    protected $casts = [
        'published_at' => 'datetime',
    ];

    protected $fillable = [
        'admin_id',
        'scope',
        'category',
        'sub_category',
        'title',
        'contents',
        'published_at',
        'deleted_at',
    ];

    protected $appends = [
        'txt_scope',
        'files'
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
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
        $query->with('admin')->orderBy('id', 'desc');

        if (isset(request()->filters) && is_array(request()->filters)) {
            foreach (request()->filters as $column => $value) {
                if ($column == 'scope' && is_array($value)) {
                    $query->where('scope', '&', array_sum($value));
                } elseif (in_array($column, ['category', 'subcategory'])) { // where in 조건
                    if (is_array($value)) {
                        $query->whereIn($column, $value);
                    } else {
                        $query->where($column, $value);
                    }
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

    public function getTxtScopeAttribute(): array
    {
        $result = [];
        foreach (dbcode('board_notices.scope') as $key => $value) {
            if ($this->scope & $key) {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    public function getFilesAttribute(): array
    {
        $files = [];
        $mediaItems = $this->getMedia('file');
        foreach ($mediaItems as $image) {
            $files[] = [
                'id' => $image->id,
                'name' => $image->file_name,
                'url' => $image->getUrl()
            ];
        }

        return $files;
    }
}
