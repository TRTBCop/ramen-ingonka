<?php


return [
    'company' => [
        'name' => '주식회사 매일국어',
        'owner' => '이동수',
        'tel' => '1800-5039',
        'business_no' => '135-86-53767',
        'order_no' => '2018-서울서초-0397', // 통신판매 신고업
        'addr' => '서울특별시 서초구 서초대로25길 67, 2층(방배동,기산빌딩)',
    ],
    'nonpayment_vcash' => 0, // 미납안내 생성시 특정 vcash 이하 조건
    'froala_editor_key' => 'cJC7bE6C2F2C2C1D2yQNDMIJg1IQNSEa1EUAi1XVFQd1EaG3C2A5D5C4D3C2D4G2H1==',
    'upload_path' => realpath(storage_path('framework/views')),
    'learn_app_url' => env('DAILYKOR_LEARN_APP_URL', 'app.dailykor.com'),
    'dict_api_key' => 'DA36B4A5CB089056044421E683EE702C', // 표균국어사전
    'unable_service_start' => [ // 서비스 시작 불가시간
        'day' => 1,
        'hour_start' => 0,
        'hour_end' => 1,
    ],
    'b2c' => [
        'free_trial' => [
            'period' => 4, // 가입 당일 포함 5일 EX) 04-23 ~ 04-27
        ],
    ],
    'referrals' => [

        /*
         '{추천코드}' = [
        'title'  => '',
        'period' => 3,
         'round' => 3,
         'register_start' => '2023-09-01 00:00:00',
         'register_end' => '2025-12-01 23:59:59',
         'purchase_start' => '2023-09-01 00:00:00',
         'purchase_end' => '2025-12-01 23:59:59',
        ]
         */
    ],
];
