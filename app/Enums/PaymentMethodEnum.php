<?php

namespace App\Enums;


enum PaymentMethodEnum: string
{
    case CA = 'CA'; // 카드
    case VA = 'VA'; // 가상계좌
    case PZ = 'PZ'; // 간편결

    public function text(): string
    {
        return config('dailykor.dbcode.payments.method')[$this->value];
    }

    public static function options(): array
    {
        return [
            'CA' => '신용카드',
            'VA' => '가상계좌',
            'PZ' => '간편결제',
        ];
    }
}
