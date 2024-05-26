<?php

return [
    'pg' => [
        'production' => [
            'mid' => 'nxca_jt_il', //현금영수증시 mid_test, 가상계좌 nx_mid_il
            'va_mid' => 'nx_mid_il', //가상계좌
            'pz_mid' => 'hecto_test', //간편결제
            'license_key' => 'ST1009281328226982205',
            'aes256_key' => 'pgSettle30y739r82jtd709yOfZ2yK5K',
            'payment_server' => 'https://tbnpg.settlebank.co.kr', // 결제 url
            'cancel_server' => 'https://tbgw.settlebank.co.kr', // 취소 url
            'cash_receipt_server' => 'https://tcash.settlebank.co.kr/pgtrans/CashReceiptMultiAction.do?_method=insertReceiptInfo', // 현금영수증
            'sales_receipt_server' => 'https://tb-nspay.settlebank.co.kr/api/cpnRcptNoAuth.do', // 매출전표
            'vbank_account_cancel' => 'https://tbgw.settlebank.co.kr/spay/APIVBank.do', // 가상계좌 발급 취소
        ],
        'development' => [
            'mid' => 'nxca_jt_il', //현금영수증시 mid_test, 가상계좌 nx_mid_il
            'va_mid' => 'nx_mid_il', //가상계좌
            'pz_mid' => 'hecto_test', //간편결제
            'license_key' => 'ST1009281328226982205',
            'aes256_key' => 'pgSettle30y739r82jtd709yOfZ2yK5K',
            'payment_server' => 'https://tbnpg.settlebank.co.kr', // 결제 url
            'cancel_server' => 'https://tbgw.settlebank.co.kr', // 취소 url
            'cash_receipt_server' => 'https://tcash.settlebank.co.kr/pgtrans/CashReceiptMultiAction.do?_method=insertReceiptInfo', // 현금영수증
            'sales_receipt_server' => 'https://tb-nspay.settlebank.co.kr/api/cpnRcptNoAuth.do', // 매출전표
            'vbank_account_cancel' => 'https://tbgw.settlebank.co.kr/spay/APIVBank.do', // 가상계좌 발급 취소
        ],
    ],
    'products' => [
        'A01' => [
            'day' => 30,
            'name' => '1개월',
            'amount' => [
                'origin' => 60000,
                'sale' => 60000,
            ],
            'month' => 1,
        ],
        'A03' => [
            'day' => 90,
            'name' => '3개월',
            'amount' => [
                'origin' => 180000,
                'sale' => 160000,
            ],
            'month' => 3,
        ],
        'A06' => [
            'day' => 180,
            'name' => '6개월',
            'amount' => [
                'origin' => 360000,
                'sale' => 300000,
            ],
            'month' => 6,
        ],
        'A12' => [
            'day' => 365,
            'name' => '12개월',
            'amount' => [
                'origin' => 720000,
                'sale' => 600000,
            ],
            'month' => 12,
        ],
    ],
];
