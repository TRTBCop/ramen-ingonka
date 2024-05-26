<?php

namespace App\Enums;


use App\Models\Academy;
use App\Models\Student;

enum ModelReservationTypeEnum: int
{
    case ACADEMY_STATUS = 10; // 학원-상태예약
    case ACADEMY_BASIC_PRICE = 11; // 학원-기본료
    case ACADEMY_MIN_STUDENT_CNT = 12; // 학원-최소이용 학생수
    case STUDENT_STATUS = 20; // 학생-상태예약


    public function text(): string
    {
        return dbcode('model_reservations.type')[$this->value] ?? '';
    }

    public static function getModel($value): string
    {
        return in_array($value, array_column(ModelReservationTypeEnum::academy(), 'value')) ? Academy::class : Student::class;
    }

    public static function options(string $type = ''): array
    {
        $academy = [
            10 => '학원-상태',
            11 => '학원-기본료',
            12 => '학원-최소학생수',
        ];

        $student = [
            20 => '학생-상태',
        ];

        if ($type == 'academy') {
            return $academy;
        }

        if ($type == 'student') {
            return $student;
        }

        return $academy + $student;

    }

    public static function academy(): array
    {
        return [
            self::ACADEMY_BASIC_PRICE, self::ACADEMY_STATUS, self::ACADEMY_MIN_STUDENT_CNT
        ];
    }
}
