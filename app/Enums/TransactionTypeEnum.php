<?php

namespace App\Enums;

enum TransactionTypeEnum: int
{
    case CHARGE_REFUND_SERVICE = 101; // 충전 - 환불처리(서비스)
    case CHARGE_SPAY = 110; // 충전 - 카드자동결제 SPAY
    case CHARGE_PG = 111; // 충전 - 포인트충전 PG
    case CHARGE_BANK = 112; // 충전 - 포인트충전 무통장
    case CHARGE_ADMIN_FCASH = 130; // 충전 - 관리자지급 비정산
    case CHARGE_ADMIN_VCASH = 131; // 충전 - 관리자지급

    case USE_SERVICE_BASIC = 200; // 사용 - 기본료
    case USE_SERVICE_CONTINUE = 201; // 사용 - 서비스이용 자동연장
    case USE_SERVICE = 202; // 사용 - 서비스이용
    case USE_CANCEL_SPAY = 210; // 사용 - 카드자동결제(SPAY) 취소
    case USE_CANCEL_PG = 211; // 사용 - 포인트충전(PG) 취소
    case USE_JOIN_FEE = 220; // 사용 - 가맹비
    case USE_ETC_EDU = 221; // 사용 - 기타 교육비
    case USE_ETC_LOW_JOIN_FEE = 222; // 사용 - 기타 1, 2학년 가맹비
    case USE_ADMIN_FCASH = 230; // 사용 - 관리자차감 비정산
    case USE_ADMIN_VCASH = 231; // 사용 - 관리자차감

    public function text(): string
    {
        return dbcode('transactions.type')[$this->value] ?? '';
    }

    public function default(): string
    {
        $default = [
            221 => 300000, // 기타 교육비
            222 => 250000, // 기타 1,2학년 가맹비
        ];
        return $default[$this->value] ?? 0;
    }

    public static function options(): array
    {
        // tinyint unsigned 255까지 사용 가능
        return [
            101 => '충전 - 서비스이용료(환불)',
            110 => '충전 - 카드자동결제(SPAY)',
            111 => '충전 - 포인트충전(PG)',
            112 => '충전 - 포인트충전(무통장)',
            130 => '충전 - 관리자지급(비정산)',
            131 => '충전 - 관리자지급',

            200 => '사용 - 기본료',
            201 => '사용 - 서비스이용료(연장)',
            202 => '사용 - 서비스이용료',
            210 => '사용 - 카드자동결제(SPAY) 취소',
            211 => '사용 - 포인트충전(PG) 취소',
            220 => '사용 - 가맹비',
            221 => '사용 - 기타 교육비',
            222 => '사용 - 기타 1, 2학년 가맹비',
            230 => '사용 - 관리자 차감(비정산)',
            231 => '사용 - 관리자 차감',
        ];
    }


    // 관리자가 지급할수있은 포인트
    public static function admin(): array
    {
        return [
            self::CHARGE_ADMIN_FCASH, self::CHARGE_ADMIN_VCASH,
            self::USE_ADMIN_FCASH, self::USE_ADMIN_VCASH,
        ];
    }

    public static function etc(): array
    {
        return [
            self::USE_ETC_EDU, self::USE_ETC_LOW_JOIN_FEE,
        ];
    }
}
