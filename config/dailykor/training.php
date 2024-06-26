<?php

return [
    'grade_group' => [ // 저, 고학년 구분
        'lower' => [3, 4, 5, 6],
        'upper' => [7, 8, 9],
    ],
    'curricula_id_by_grade_term' => [ // grade 와 term 으로 curricula.id 를 찾는다
        3 => [
            1 => 16, // 초등 3학년 1학기
            2 => 17, // 초등 3학년 2학기
        ],
        4 => [
            1 => 18, // 초등 4학년 1학기
            2 => 19, // 초등 4학년 2학기
        ],
        5 => [
            1 => 20, // 초등 5학년 1학기
            2 => 21, // 초등 5학년 2학기
        ],
        6 => [
            1 => 22, // 초등 6학년 1학기
            2 => 23, // 초등 6학년 2학기
        ],
        7 => [
            1 => 246, // 중등 1학년 1학기
            2 => 247, // 중등 1학년 2학기
        ],
        8 => [
            1 => 248, // 중등 2학년 1학기
            2 => 249, // 중등 2학년 2학기
        ],
        9 => [
            1 => 256, // 중등 3학년 1학기
            2 => 257, // 중등 3학년 2학기
        ],
    ]
];
