<?php

namespace App\Enums;

enum CurriculumElementEnum: int
{
    case NOT_SPECIFIED = 0;
    case SUWAYEONSAN = 100;  // 수와연산
    case GYUJIKSEONG = 200;    // 규칙성
    case DOHYUNG = 300;         // 도형
    case CHEUKJEONG = 400;      // 측정
    case JARYOWAGANEUNG = 500; // 자료와가능성
    case MUNJAWASIK = 600;    // 문자와 식
    case HAMSU = 700;           // 함수
    case GIHA = 800;            // 기하
    case HWAKRYULWATONGGYE = 900; // 확률과통계

    public function text(): string
    {
        return dbcode('curricula.element')[$this->value] ?? '';
    }

    public static function options(): array
    {
        return [
            0 => '속성없음',
            100 => '수와 연산',
            200 => '규칙성',
            300 => '도형',
            400 => '측정',
            500 => '자료와 가능성',
            600 => '문자와 식',
            700 => '함수',
            800 => '기하',
            900 => '확률과 통계',
        ];
    }

    /**
     *  저학년
     */
    public static function lower(): array
    {
        return [
            self::SUWAYEONSAN, self::GYUJIKSEONG, self::DOHYUNG, self::CHEUKJEONG, self::JARYOWAGANEUNG
        ];
    }

    /**
     *  고학년
     */
    public static function upper(): array
    {
        return [
            self::SUWAYEONSAN, self::MUNJAWASIK, self::HAMSU, self::GIHA, self::HWAKRYULWATONGGYE
        ];
    }
}

abstract class TrainingConceptTextType
{
    public const READINGS = 'readings';
    public const SUMMARIZATIONS = 'summarizations';
    public const REINFORCEMENTS = 'reinforcements';
    public const DONE = 'done';
}
