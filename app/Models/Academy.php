<?php

namespace App\Models;

use App\Enums\AcademyStatusEnum;
use App\Enums\StudentStatusEnum;
use App\Traits\ActivityLogTrait;
use App\Traits\PaginatableTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Tags\HasTags;
use Spatie\Permission\Traits\HasRoles;

/**
 * App\Models\Academy
 *
 * @property int $id
 * @property string $name 학원명
 * @property string $zipcode 우편번호
 * @property string $address 주소
 * @property string $address2 상세주소
 * @property string $phone 연락처
 * @property string $staff_phone 담당자연락처
 * @property string $staff_name 담당자명
 * @property string $staff_email 담당자이메일
 * @property AcademyStatusEnum $status 상태
 * @property array|null $extra 확장컬럼
 * @property string|null $manager_memo 관리자메모
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $actions
 * @property-read int|null $actions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read array $files
 * @property-read string $logo
 * @property-read string $txt_status
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Payment> $payments
 * @property-read int|null $payments_count
 * @property \Illuminate\Database\Eloquent\Collection<int, \Spatie\Tags\Tag> $tags
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Student> $students
 * @property-read int|null $students_count
 * @property-read int|null $tags_count
 * @method static \Database\Factories\AcademyFactory factory($count = null, $state = [])
 * @method static Builder|Academy listFilter()
 * @method static Builder|Academy newModelQuery()
 * @method static Builder|Academy newQuery()
 * @method static Builder|Academy onlyTrashed()
 * @method static Builder|Academy query()
 * @method static Builder|Academy whereAddress($value)
 * @method static Builder|Academy whereAddress2($value)
 * @method static Builder|Academy whereCreatedAt($value)
 * @method static Builder|Academy whereDeletedAt($value)
 * @method static Builder|Academy whereExtra($value)
 * @method static Builder|Academy whereId($value)
 * @method static Builder|Academy whereManagerMemo($value)
 * @method static Builder|Academy whereName($value)
 * @method static Builder|Academy wherePhone($value)
 * @method static Builder|Academy whereStaffEmail($value)
 * @method static Builder|Academy whereStaffName($value)
 * @method static Builder|Academy whereStaffPhone($value)
 * @method static Builder|Academy whereStatus($value)
 * @method static Builder|Academy whereUpdatedAt($value)
 * @method static Builder|Academy whereZipcode($value)
 * @method static Builder|Academy withAllTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static Builder|Academy withAllTagsOfAnyType($tags)
 * @method static Builder|Academy withAnyTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static Builder|Academy withAnyTagsOfAnyType($tags)
 * @method static Builder|Academy withTrashed()
 * @method static Builder|Academy withoutTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static Builder|Academy withoutTrashed()
 * @mixin \Eloquent
 */
class Academy extends Authenticatable implements HasMedia
{
    use HasFactory;
    use HasRoles;
    use HasApiTokens;
    use ActivityLogTrait;
    use SoftDeletes;
    use InteractsWithMedia;
    use HasTags;
    use PaginatableTrait;
    use Notifiable;

    protected string $guard = 'academy';

    protected $casts = [
        'extra' => 'array',
        'status' => AcademyStatusEnum::class,
        'joined_at' => 'datetime',
    ];

    protected $attributes = [];

    protected $fillable = [
        'name',
        'zipcode',
        'address',
        'address2',
        'phone',
        'staff_phone',
        'staff_name',
        'staff_email',
        'president_name',
        'manager_memo',
        'status',
        'extra',
    ];

    protected $hidden = [
        'manager_memo',
    ];


    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'txt_status',
    ];


    /*
     * 학생 1:n
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function activeStudents(): HasMany
    {
        return $this->hasMany(Student::class)->where('status', StudentStatusEnum::IN_USE);
    }


    /*
     *  결제
     */
    public function payments(): MorphMany
    {
        return $this->morphMany(Payment::class, 'model');
    }

    public function getLogoAttribute(): string
    {
        $avatar = $this->getFirstMedia('logo');

        return $avatar ? $avatar->getFullUrl() : url('/media/avatars/blank.png');
    }


    // 첨부파일
    public function getFilesAttribute(): array
    {
        $files = [];
        $mediaItems = $this->getMedia('file');
        foreach ($mediaItems as $image) {
            $files[] = [
                'id' => $image->id,
                'name' => $image->file_name,
                'url' => $image->getUrl(),
            ];
        }

        return $files;
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
        $query->orderBy('id', 'desc');

        if (isset(request()->filters) && is_array(request()->filters)) {
            foreach (request()->filters as $column => $value) {
                // 태그 검색
                if ($column == 'tags' && is_array($value)) {
                    $query->withAnyTags($value, 'admin.academies');
                }

                if (in_array($column, ['status'])) { // where in 조건
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
                $columns = ['name', 'staff_name'];
                foreach ($columns as $column) {
                    $query->orWhere($column, 'like', '%'.trim(request()->filter_text).'%');
                }
            });
        }

        return $query;
    }

    public function getTxtStatusAttribute(): string
    {
        return $this->status ? $this->status->text() : '';
    }
}
