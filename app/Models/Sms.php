<?php

namespace App\Models;

use App\Traits\ActivityLogTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Sms
 *
 * @property int $id
 * @property string|null $model_type
 * @property int|null $model_id
 * @property string $subject 제목
 * @property string $send_phone 발신번호
 * @property string $dest_phone 수신번호
 * @property string $template_code 알림톡템플릿코드
 * @property string $msg 내용
 * @property int $is_debug 디버그유무
 * @property string $created_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $actions
 * @property-read int|null $actions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @method static \Database\Factories\SmsFactory factory($count = null, $state = [])
 * @method static Builder|Sms listFilter()
 * @method static Builder|Sms newModelQuery()
 * @method static Builder|Sms newQuery()
 * @method static Builder|Sms query()
 * @method static Builder|Sms whereCreatedAt($value)
 * @method static Builder|Sms whereDestPhone($value)
 * @method static Builder|Sms whereId($value)
 * @method static Builder|Sms whereIsDebug($value)
 * @method static Builder|Sms whereModelId($value)
 * @method static Builder|Sms whereModelType($value)
 * @method static Builder|Sms whereMsg($value)
 * @method static Builder|Sms whereSendPhone($value)
 * @method static Builder|Sms whereSubject($value)
 * @method static Builder|Sms whereTemplateCode($value)
 * @mixin \Eloquent
 */
class Sms extends Model
{
    use HasFactory, ActivityLogTrait;

    public $timestamps = false;

    protected $casts = [
    ];

    protected $fillable = [
        'model_type',
        'model_id',
        'send_phone',
        'dest_phone',
        'template_code',
        'msg',
        'is_debug',
    ];

    /**
     *
     * 목록 검색 필터
     * @alias listFilter
     * @param $query
     * @return Builder
     */
    function scopeListFilter($query): Builder
    {
        $query->orderBy('id', 'desc');

        /*
        if (isset(request()->filters) && is_array(request()->filters)) {
            foreach (request()->filters as $column => $value) {

                if (in_array($column, ['send_phone', 'dest_phone'])) { // where in 조건
                    if (is_array($value)) {
                        $query->whereIn($column, $value);
                    } else {
                        $query->where($column, $value);
                    }
                }
            }
        }
        */

        // 키워드 검색
        if (isset(request()->filter_text) && request()->filter_text) {
            $query->where(function ($query) {
                $columns = ['send_phone', 'dest_phone'];
                foreach ($columns as $column) {
                    $query->orWhere($column, 'like', '%'.trim(request()->filter_text).'%');
                }
            });
        }

        return $query;
    }
}
