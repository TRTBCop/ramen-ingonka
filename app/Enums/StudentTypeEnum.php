<?php

namespace App\Enums;


enum StudentTypeEnum: int
{
    case ELEMENTARY = 1; // 초등
    case MIDDLE_HIGH = 2; // 중고등

    public function text(): string
    {
        return dbcode('students.type')[$this->value] ?? '';
    }

    public static function options(): array
    {
        return [
            1 => '초등',
            2 => '중고등',
        ];
    }
}
