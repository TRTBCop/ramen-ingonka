<?php

namespace App\Models;

use App\Traits\ActivityLogTrait;
use App\Traits\AvatarTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;


/**
 * App\Models\Admin
 *
 * @property int $id
 * @property string $name 관리자명
 * @property string $access_id 아이디
 * @property string $password 비밀번호
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $actions
 * @property-read int|null $actions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read string $avatar
 * @property-read array $role_names
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\AdminFactory factory($count = null, $state = [])
 * @method static Builder|Admin listFilter()
 * @method static Builder|Admin newModelQuery()
 * @method static Builder|Admin newQuery()
 * @method static Builder|Admin onlyTrashed()
 * @method static Builder|Admin permission($permissions)
 * @method static Builder|Admin query()
 * @method static Builder|Admin role($roles, $guard = null)
 * @method static Builder|Admin whereAccessId($value)
 * @method static Builder|Admin whereCreatedAt($value)
 * @method static Builder|Admin whereDeletedAt($value)
 * @method static Builder|Admin whereId($value)
 * @method static Builder|Admin whereName($value)
 * @method static Builder|Admin wherePassword($value)
 * @method static Builder|Admin whereRememberToken($value)
 * @method static Builder|Admin whereUpdatedAt($value)
 * @method static Builder|Admin withTrashed()
 * @method static Builder|Admin withoutTrashed()
 * @mixin \Eloquent
 */
class Admin extends Authenticatable implements HasMedia
{
    use HasFactory, HasRoles, SoftDeletes, HasApiTokens, ActivityLogTrait, InteractsWithMedia, AvatarTrait;

    protected string $guard = 'admin';

    protected $casts = [
        'extra' => 'array',
    ];

    protected $fillable = [
        'name',
        'access_id',
        'password',
        'remember_token',
    ];

    protected $hidden = [
        'password',
    ];

    public function getRoleNamesAttribute(): array
    {
        return $this->roles->pluck('name')->toArray();
    }


    /**
     *
     * 목록 검색 필터
     * @alias listFilter
     * @param $query
     * @return Builder
     */
    function scopeListFilter($query): Builder
    {
        $academies = $query->orderBy('id', 'desc');

        // 키워드 검색
        if (isset(request()->filter_text) && request()->filter_text) {
            $query->where(function ($query) {
                $columns = ['name', 'access_id'];
                foreach ($columns as $column) {
                    $query->orWhere($column, 'like', '%'.trim(request()->filter_text).'%');
                }
            });
        }

        return $academies;
    }

    /*
     * https://spatie.be/docs/laravel-medialibrary/v10/working-with-media-collections/defining-media-collections#content-single-file-collections
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')->singleFile();
    }
}
