<?php

namespace App\Models;

use App\Enums\PaymentMethodEnum;
use App\Enums\PaymentStatusEnum;
use App\Traits\ActivityLogTrait;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Spatie\Activitylog\Models\Activity;

/**
 * App\Models\Payment
 *
 * @property int $id
 * @property string $model_type
 * @property int $model_id
 * @property string $trd_no PG 거래번호
 * @property PaymentMethodEnum $method 결제방법
 * @property string $od_id 주문번호
 * @property string $od_name 주문자명
 * @property int $amount 결제금액
 * @property int $cancel_amount 취소금액
 * @property PaymentStatusEnum $status 결제상태
 * @property array|null $extra 기록용
 * @property Carbon|null $canceled_at 취소일시
 * @property Carbon|null $approved_at 결제일시
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at 삭제일시
 * @property-read Collection<int, Activity> $actions
 * @property-read int|null $actions_count
 * @property-read Collection<int, Activity> $activities
 * @property-read int|null $activities_count
 * @property-read string $code_method
 * @property-read string $txt_method
 * @property-read string $txt_status
 * @property-read Model|\Eloquent $model
 * @method static \Database\Factories\PaymentFactory factory($count = null, $state = [])
 * @method static Builder|Payment listFilter(?\App\Models\Academy $academy = null)
 * @method static Builder|Payment newModelQuery()
 * @method static Builder|Payment newQuery()
 * @method static Builder|Payment onlyTrashed()
 * @method static Builder|Payment query()
 * @method static Builder|Payment whereAmount($value)
 * @method static Builder|Payment whereApprovedAt($value)
 * @method static Builder|Payment whereCancelAmount($value)
 * @method static Builder|Payment whereCanceledAt($value)
 * @method static Builder|Payment whereCreatedAt($value)
 * @method static Builder|Payment whereDeletedAt($value)
 * @method static Builder|Payment whereExtra($value)
 * @method static Builder|Payment whereId($value)
 * @method static Builder|Payment whereMethod($value)
 * @method static Builder|Payment whereModelId($value)
 * @method static Builder|Payment whereModelType($value)
 * @method static Builder|Payment whereOdId($value)
 * @method static Builder|Payment whereOdName($value)
 * @method static Builder|Payment whereStatus($value)
 * @method static Builder|Payment whereTrdNo($value)
 * @method static Builder|Payment whereUpdatedAt($value)
 * @method static Builder|Payment withTrashed()
 * @method static Builder|Payment withoutTrashed()
 * @mixin Eloquent
 */
class Payment extends Model
{
    use HasFactory, ActivityLogTrait, SoftDeletes;

    protected $fillable = [
        'model_type',
        'model_id',
        'od_id',
        'od_name',
        'method',
        'trd_no',
        'pg_msg',
        'pg_code',
        'amount',
        'cancel_amount',
        'status',
        'extra',
        'canceled_at',
        'approved_at',
    ];

    protected $casts = [
        'extra' => 'array',
        'status' => PaymentStatusEnum::class,
        'method' => PaymentMethodEnum::class,
        'approved_at' => 'datetime',
        'canceled_at' => 'datetime',
    ];

    protected $attributes = [
        'extra' => '{}',
    ];


    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'txt_status',
        'txt_method',
    ];

    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * 목록 검색 필터
     * @alias listFilter
     * @param $query
     * @param Academy|null $academy
     * @return Builder
     */
    function scopeListFilter($query, ?Academy $academy = null): Builder
    {
        $model = $query->orderBy('id', 'desc');

        if (request()->student_id) {
            $query->where([
                'model_type' => Student::class,
                'model_id' => request()->student_id,
            ]);
        } else if (request()->is_b2c) {
            $query
                ->with(['model'])
                ->where([
                    'model_type' => Student::class,
                ]);

        } elseif ($academy) { // 특정학원-결제목록
            $query->where([
                'model_type' => Academy::class,
                'model_id' => $academy->id,
            ]);
        }

        if (isset(request()->filters) && is_array(request()->filters)) {
            foreach (request()->filters as $column => $value) {
                switch ($column) {
                    case 'start_approved_at':  // 결제완료시작일
                        $query->where('approved_at', '>=', request()->filters['start_approved_at'].' 00:00:00');
                        break;

                    case 'end_approved_at': // 결제완료종료일
                        $query->where('approved_at', '<=', request()->filters['end_approved_at'].' 23:59:29');
                        break;
                    case 'academy_id': // 학원
                        if (is_array($value)) {
                            $query->whereIn('model_id', $value);
                        } else {
                            $query->where('model_id', $value);
                        }
                        break;

                    case 'method': // 결제수단
                    case 'status': // 상태
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
                $columns = ['od_name', 'trd_no', 'od_id'];
                foreach ($columns as $column) {
                    $query->orWhere($column, 'like', '%'.trim(request()->filter_text).'%');
                }
            });
        }

        return $model;
    }


    public function getTxtStatusAttribute(): string
    {
        return $this->status ? $this->status->text() : '';
    }

    public function getTxtMethodAttribute(): string
    {
        return $this->method ? $this->method->text() : '';
    }

    public function getCodeMethodAttribute(): string
    {
        return $this->method ? $this->method->name : '';
    }
}
