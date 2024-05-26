<?php

namespace App\Enums;


enum PaymentCashReceiptEnum: int
{
    case CARD_NUMBER = 1; // 카드번호
    case RRN = 2; // 주민등록번호
    case BRN = 3; // 사업자등록번호
    case PHONE_NUMBER = 4; // 휴대폰번호

    public function text(): string
    {
        return dbcode('payment.extra.identity_gb')[$this->value];
    }

    public static function options(): array
    {
        return [
            1 => '카드번호',
            2 => '주민번호',
            3 => '사업자번호',
            4 => '휴대전화번호',
        ];
    }
}
