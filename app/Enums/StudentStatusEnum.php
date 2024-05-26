<?php

namespace App\Enums;

enum StudentStatusEnum: int
{
    case WITHDRAW = -3; // 탈퇴
    case STOP = -2; // 학습중지
    case EXPIRED = -1; // 사용만료
    case STANDBY = 0; // 사용전 (default B2C)

    case FREE = 1; // 무료체험

    case IN_USE = 2; // 사용중

    public function text(): string
    {
        return config('dailykor.dbcode.students.status')[$this->value] ?? '';
    }

    public static function options(): array
    {
        return [
            -3 => '탈퇴',
            -2 => '학습중지',
            -1 => '사용만료',
            0 => '사용전',
            1 => '무료체험',
            2 => '사용중',
        ];
    }

    /**
     * 진단테스트 횟수
     *
     * @return int
     */
    public function canTestCount(): int
    {
        return match ($this) {
            self::FREE => 1,
            self::IN_USE => 100,
            default => 0,
        };
    }
}
