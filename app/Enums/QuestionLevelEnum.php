<?php

namespace App\Enums;

enum QuestionLevelEnum: int
{
    case LOW = 1;
    case BELOW_MEDIUM = 2;
    case MEDIUM = 3;
    case ABOVE_MEDIUM = 4;
    case HIGH = 5;

    public function text(): string
    {
        return dbcode('questions.level')[$this->value] ?? '';
    }

    public static function options(): array
    {
        return [
            1 => '하',
            2 => '중하',
            3 => '중',
            4 => '중상',
            5 => '상',
        ];
    }
}
