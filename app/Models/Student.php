<?php

namespace App\Models;

use App\Enums\StudentStatusEnum;
use App\Enums\StudentTypeEnum;
use App\Traits\ActivityLogTrait;
use App\Traits\AvatarTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * App\Models\Student
 *
 * @property int $id
 * @property int|null $academy_id academies.id
 * @property string $access_id 아이디
 * @property string $password 비밀번호
 * @property string $address 주소
 * @property string $school_name 학교명
 * @property int $grade 학년
 * @property int $term 학기
 * @property string $name 학생 이름
 * @property int $profile_img_type 프로필 이미지
 * @property string $phone 학생 연락처
 * @property string $parents_name 학부모 이름
 * @property string $parents_phone 학부모 연락처
 * @property \Illuminate\Support\Carbon|null $birth_date 생년월일
 * @property string|null $manager_memo 관리자 메모
 * @property \Illuminate\Support\Carbon|null $service_start_date 서비스시작일
 * @property \Illuminate\Support\Carbon|null $service_end_date 서비스종료일
 * @property int $marketing_consent 마케팅 동의
 * @property string|null $last_login_at 마지막로그인일시
 * @property string|null $kakao_id 소셜로그인(카카오)
 * @property string|null $naver_id 소셜로그인(네이버)
 * @property StudentStatusEnum $status 상태
 * @property array|null $extra 확장컬럼
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property StudentTypeEnum $type
 * @property-read \App\Models\Academy|null $academy
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $actions
 * @property-read int|null $actions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Kalnoy\Nestedset\Collection<int, \App\Models\Curriculum> $curricula
 * @property-read int|null $curricula_count
 * @property-read string $avatar
 * @property-read array $role_names
 * @property-read string $txt_status
 * @property-read string $txt_type
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Payment> $payments
 * @property-read int|null $payments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TrainingResult> $training_results
 * @property-read int|null $training_results_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TestResult> $test_results
 * @property-read int|null $test_results_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StudentPhone> $verifyPhone
 * @property-read int|null $verify_phone_count
 * @method static \Database\Factories\StudentFactory factory($count = null, $state = [])
 * @method static Builder|Student inUse()
 * @method static Builder|Student listFilter(?\App\Models\Academy $academy = null)
 * @method static Builder|Student newModelQuery()
 * @method static Builder|Student newQuery()
 * @method static Builder|Student onlyTrashed()
 * @method static Builder|Student query()
 * @method static Builder|Student whereAcademyId($value)
 * @method static Builder|Student whereAccessId($value)
 * @method static Builder|Student whereAddress($value)
 * @method static Builder|Student whereBirthDate($value)
 * @method static Builder|Student whereCreatedAt($value)
 * @method static Builder|Student whereDeletedAt($value)
 * @method static Builder|Student whereExtra($value)
 * @method static Builder|Student whereGrade($value)
 * @method static Builder|Student whereId($value)
 * @method static Builder|Student whereKakaoId($value)
 * @method static Builder|Student whereLastLoginAt($value)
 * @method static Builder|Student whereManagerMemo($value)
 * @method static Builder|Student whereMarketingConsent($value)
 * @method static Builder|Student whereName($value)
 * @method static Builder|Student whereNaverId($value)
 * @method static Builder|Student whereParentsName($value)
 * @method static Builder|Student whereParentsPhone($value)
 * @method static Builder|Student wherePassword($value)
 * @method static Builder|Student wherePhone($value)
 * @method static Builder|Student whereRememberToken($value)
 * @method static Builder|Student whereSchoolName($value)
 * @method static Builder|Student whereServiceEndDate($value)
 * @method static Builder|Student whereServiceStartDate($value)
 * @method static Builder|Student whereStatus($value)
 * @method static Builder|Student whereTerm($value)
 * @method static Builder|Student whereUpdatedAt($value)
 * @method static Builder|Student withTrashed()
 * @method static Builder|Student withoutTrashed()
 * @mixin \Eloquent
 */
class Student extends Authenticatable implements HasMedia
{
    use HasFactory;
    use SoftDeletes;
    use HasApiTokens;
    use ActivityLogTrait;
    use InteractsWithMedia;
    use AvatarTrait;
    use Notifiable;


    protected string $guard = 'student';

    protected $casts = [
        'type' => StudentTypeEnum::class,
        'status' => StudentStatusEnum::class,
        'extra' => 'array',
        'mh_voca_result' => 'array',
        'service_start_date' => 'date:Y-m-d',
        'service_end_date' => 'date:Y-m-d',
        'birth_date' => 'date:Y-m-d',
    ];

    protected $attributes = [
    ];

    protected $fillable = [
        'academy_id',
        'name',
        'profile_img_type',
        'access_id',
        'password',
        'phone',
        'address',
        'grade',
        'parents_name',
        'parents_phone',
        'birth_date',
        'status',
        'remember_token',
        'manager_memo',
        'kakao_id',
        'naver_id',
        'marketing_consent',
        'extra',
        'service_start_date',
        'service_end_date',
        'referral_code',
        'last_login_at',
    ];

    protected $hidden = [
        'manager_memo',
        'password',
        'remember_token',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'txt_status',
    ];


    public function academy(): BelongsTo
    {
        return $this->belongsTo(Academy::class);
    }

    public function payments(): MorphMany
    {
        return $this->morphMany(Payment::class, 'model');
    }

    /*
     * 학생학습결과 n:m
     * 피벗테이블 student_curriculum
     */
    public function curricula(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Curriculum::class, 'student_curriculum')->withPivot([
            'extra',
            'completed_at',
            'updated_at'
        ]);
    }

    public function training_results(): HasMany
    {
        return $this->hasMany(TrainingResult::class);
    }

    public function test_results(): HasMany
    {
        return $this->hasMany(TestResult::class);
    }


    /*
     * 사용중 학생
     */
    public function scopeInUse($query): Builder
    {
        $query->where('status', StudentStatusEnum::IN_USE);
        return $query;
    }

    /**
     * 목록 검색 필터
     * @param $query
     * @param Academy|null $academy
     * @return Builder
     */
    public function scopeListFilter($query, ?Academy $academy = null): Builder
    {
        if ($academy) { // 학원하위-학생
            $query->where('academy_id', $academy->id);
        } else { // 학생 전체
            $query->with(['academy']);
        }

        $query->orderBy('id', 'desc');

        if (isset(request()->filters) && is_array(request()->filters)) {
            foreach (request()->filters as $column => $value) {
                switch ($column) {
                    case 'b2c':  // b2c 유무
                        $query->whereNull('academy_id');
                        break;

                    case 'academy_id': // 학원
                        if (is_array($value)) {
                            $query->whereIn('academy_id', $value);
                        } else {
                            $query->where('academy_id', $value);
                        }
                        break;

                    case 'status': // 상태
                        if (is_array($value)) {
                            $query->whereIn($column, $value);
                        } else {
                            $query->where($column, $value);
                        }
                        break;

                    case 'grade': // 학년
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
        if (isset(request()->filter_text) && request()->filter_text) {
            $query->where(function ($query) {
                $columns = ['id', 'name', 'access_id', 'phone', 'parents_name', 'parents_phone'];
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

    public function getTxtTypeAttribute(): string
    {
        return $this->type ? $this->type->text() : '';
    }

    /**
     * 인증된 휴대폰 번호
     * @return hasMany
     */
    public function verifyPhone(): hasMany
    {
        return $this->hasMany(StudentPhone::class);
    }

    /*
     * https://spatie.be/docs/laravel-medialibrary/v10/working-with-media-collections/defining-media-collections#content-single-file-collections
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')->singleFile();
    }

    public function isB2c(): bool
    {
        return is_null($this->academy_id);
    }

    public function getExtraAttribute($value)
    {
        return $value === null ? null : json_decode($value, true);
    }

    public function getRoleNamesAttribute(): array
    {
        return [];
    }

    /**
     * 학생의 이용기간이 지났는지 여부
     */
    public function isExpired(): bool
    {
        // service_end_date가 없으면 기간 만료
        if (!isset($this->service_end_date)) {
            return true;
        }

        $targetDate = Carbon::createFromFormat('Y-m-d H:i:s', $this->service_end_date);
        $today = Carbon::today();

        // 기간 지났는지 체크
        $isPast = $today->gt($targetDate);

        return $isPast || $this->status == StudentStatusEnum::EXPIRED;
    }

    /**
     * 무료 체험 학생 여부
     */
    public function isFree(): bool
    {
        return $this->status == StudentStatusEnum::FREE;
    }


    /**
     * 무료 체험 완료 여부
     */
    public function isFreeExpired(): bool
    {
        if (!isset($this->extra['free_trial'])) {
            return true;
        }



        return $this->extra['free_trial']['expired'];
    }
}
