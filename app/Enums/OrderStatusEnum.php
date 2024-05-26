<?php

namespace App\Enums;


enum OrderStatusEnum: int
{
    case RETURN_REQUEST = -2;
    case ORDER_CANCEL = -1;
    case PAYMENT_PENDING = 0;
    case SHIPMENT_READY = 1;
    case SHIPPING = 2;
    case DELIVERED = 3;

    public function text(): string
    {
        return config('dailykor.dbcode.order.status')[$this->value];
    }

    public static function options(): array
    {
        return [
            -2 => '반품요청',
            -1 => '주문취소',
            0 => '결제대기',
            1 => '배송준비중',
            2 => '배송중',
            3 => '배송완료',
        ];
    }
}
