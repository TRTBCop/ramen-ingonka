<?php

namespace App\Enums;


enum PaymentStatusEnum: int
{
    case PARTIAL_CANCEL = -2; // 결제취소(부분)
    case CANCEL = -1; // 결제취소
    case WAITING = 0; // 입금대기
    case APPROVE = 1; // 결제완료

    public function text(): string
    {
        return config('dailykor.dbcode.payments.status')[$this->value];
    }

    public static function options(): array
    {
        return [
            -2 => '결제취소(부분)',
            -1 => '결제취소(전체)',
            0 => '입금대기',
            1 => '결제완료',
        ];
    }
}
