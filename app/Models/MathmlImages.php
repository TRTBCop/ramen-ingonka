<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MathmlImages
 *
 * @property string $md5_key
 * @property string $mml mml(MathML)로 생성된 수식
 * @property string $svg svg 변환된 mml
 * @property int $width svg 길이
 * @property int $height svg 높이
 * @property \Illuminate\Support\Carbon $created_at
 * @method static \Illuminate\Database\Eloquent\Builder|MathmlImages newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MathmlImages newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MathmlImages query()
 * @method static \Illuminate\Database\Eloquent\Builder|MathmlImages whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MathmlImages whereHeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MathmlImages whereMd5Key($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MathmlImages whereMml($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MathmlImages whereSvg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MathmlImages whereWidth($value)
 * @mixin \Eloquent
 */
class MathmlImages extends Model
{
    use HasFactory;

    const UPDATED_AT = null;

    protected $primaryKey = 'md5_key';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'md5_key',
        'mml',
        'svg',
        'width',
        'height',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
    ];
}
