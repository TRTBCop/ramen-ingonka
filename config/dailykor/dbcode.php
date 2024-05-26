<?php

/*
 * A~Z 순서대로 테이블이름과 동일하게
 */

use App\Enums\AcademyStatusEnum;
use App\Enums\CurriculumElementEnum;
use App\Enums\OrderStatusEnum;
use App\Enums\PaymentCashReceiptEnum;
use App\Enums\PaymentMethodEnum;
use App\Enums\PaymentStatusEnum;
use App\Enums\QuestionLevelEnum;
use App\Enums\StudentStatusEnum;

return [
    'academies' => [
        'status' => AcademyStatusEnum::options(),
    ],
    'board_notices' => [
        'scope' => [ // 비트연산
            1 => '학원',
            2 => '학생',
            4 => '브랜드',
        ],
        'category' => [
            ['name' => '공지'],
            ['name' => '이벤트'],
            ['name' => '업데이트'],
        ],
    ],
    'curricula' => [
        'element' => CurriculumElementEnum::options(),
    ],
    'order' => [
        'status' => OrderStatusEnum::options(),
    ],
    'payments' => [
        'status' => PaymentStatusEnum::options(),
        'method' => PaymentMethodEnum::options(),
        'extra' => [
            'identity_gb' => PaymentCashReceiptEnum::options(),
        ],
    ],
    'questions' => [
        'level' => QuestionLevelEnum::options(),
        'answers' => [
            'type' => [
                1 => '입력형',
                2 => '선지형',
                3 => '순서맞추기',
            ],
            'action' => [
                1 => '문해',
                2 => '계산',
                3 => '추론',
                4 => '문제해결',
            ],
        ],
    ],
    'students' => [
        'grade' => [
            /*
            1 => '초등 1',
            2 => '초등 2',
            */
            3 => '초등 3',
            4 => '초등 4',
            5 => '초등 5',
            6 => '초등 6',
            7 => '중등 1',
            8 => '중등 2',
            9 => '중등 3',
        ],
        'status' => StudentStatusEnum::options(),
    ],
    'trainings' => [
        'stage' => [
            1 => '개념',
            2 => '유형',
            3 => '서술형',
        ]
    ],
    'training_results' => [
        'round' => [
            0 => '1R',
            1 => '2R',
            2 => '3R',
        ]
    ],
    'test_questions' => [
        'level' => [
            1 => '하',
            2 => '중하',
            3 => '중',
            4 => '중상',
            5 => '상',
        ]
    ]
];
