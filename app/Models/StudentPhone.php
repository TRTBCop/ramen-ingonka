<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;

/**
 * App\Models\StudentPhone
 *
 * @property int $id
 * @property string $phone students.phone
 * @property string $code 인증번호
 * @property int $verified
 * @property int|null $student_id students.id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\Student|null $student
 * @method static \Illuminate\Database\Eloquent\Builder|StudentPhone newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentPhone newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentPhone query()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentPhone unverified()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentPhone verified()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentPhone whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentPhone whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentPhone whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentPhone wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentPhone whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentPhone whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentPhone whereVerified($value)
 * @mixin \Eloquent
 */
class StudentPhone extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'student_id',
        'code',
        'phone',
        'verified'
    ];

    /**
     * 학생 정보
     * @return HasOne
     */
    public function student(): HasOne
    {
        return $this->hasOne(Student::class);
    }

    // 'verified' 컬럼을 기준으로 인증된 사용자만 조회하는 Local Scope
    public function scopeVerified($query)
    {
        return $query->where('verified', true);
    }

    // 'verified' 컬럼을 기준으로 인증되지 않은 사용자만 조회하는 Local Scope
    public function scopeUnverified($query)
    {
        return $query->where('verified', false);
    }
}
