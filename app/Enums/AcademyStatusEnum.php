<?php

namespace App\Enums;


enum AcademyStatusEnum: int
{
    case UNPAID_STOP = -2; // 미납정지
    case STOP = -1; // 정지
    case FREE = 0; // 무료사용
    case PREMIUM = 1; // 정상

    public function text(): string
    {
        return dbcode('academies.status')[$this->value] ?? '';
    }

    public static function options(): array
    {
        return [
            -2 => '미납정지',
            -1 => '일시정지',
            0 => '무료사용',
            1 => '정상',
        ];
    }
}
