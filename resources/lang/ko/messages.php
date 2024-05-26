<?php

return [
    'unable_to_start_service_due_to_calibration' => ':hour_start 시 ~ :hour_end 시 사이에는 서비스 시작을 할 수 없습니다.',
    'error_occurred' => '문제가발생했습니다. :comment', // 500
    'invalid_request' => '잘못된요청입니다. :comment', // 403
    'error_401' => '접근권한이 없습니다', // 401,
    'error_403' => '접근권한이 없습니다.',
    'already_in_service' => '이미 서비스 중입니다.',
    'the_data_is_already_registered' => '이미 등록된 데이터 입니다.',
    'have_a_reservation_status' => '학생 상태 변경예약을 취소후 서비스시작을 진행해야 합니다.',
    'admin' => [
        'payments' => [
            'fail_transaction' => '포인트 지급 실패 하였습니다.',
            'fail_transaction_constraint' => '포인트 지급내역이 남아있어 삭제 할수 없습니다.',
        ],
        'curricula' => [
            'top_node_not_found' => '최상위 노드를 찾을수 없습니다.',
            'child_exists_cannot_delete' => '자식 노드가 있으면 삭제가 불가합니다. (모두 제거후 삭제해주세요)',
            'training_exists_cannot_delete' => '연결된 트레이닝이 있습니다. 삭제가 불가합니다. (모두 제거후 삭제해주세요)'
        ],
        'tests' => [
            'child_exists_cannot_delete' => '하위 문제가 있어 삭제가 불가합니다. (모두 제거후 삭제해주세요)',
        ],
        'test_questions' => [

        ]
    ],
    'app' => [
        'common' => [
            'success' => ':name 성공',
            'failed' => ':name 실패',
        ],
        'phone_verification' => [
            'check_verification_code' => '인증번호를 확인해주세요',
        ],
        'students' => [
            'account_does_not_exist' => '아이디가 존재하지 않습니다.',
            'referral_check' => [
                'does_not_exist' => '존재하지 않는 추천 코드입니다.',
                'non_membership_period' => '가입기간이 아닙니다.',
            ],
            'duplicate_data_found' => '이름과 학부모휴대폰 번호가 같은 데이터가 존재합니다.',
            'password_match_failure' => '비밀번호가 맞지 않습니다.',
            'access_id_available' => '사용 가능한 아이디입니다.',
            'free_expired' => '무료체험이 종료되었습니다.',
            'service_expired' => '서비스 이용중이 아닙니다.',
            'free_cannot_update_semester' => '무료체험 중에는 학년 학기 변경이 불가능합니다.',
            'free_guard' => '무료체험 중에는 이용할 수 없는 서비스입니다.',
            'grade_semester_set' => '학년학기를 설정해주세요.',
            'withdraw_academy' => 'b2c회원만 탈퇴 가능합니다!',
            'required_social_account' => '하나 이상의 계정과 연동 되어있어야 합니다.',
        ],
        'trainings' => [
            'free_only_first_study_guard' => '무료체험은 설정된 학년 학기의 첫번째 소단원의 학습만 진행 가능합니다.',
            'not_completed_result' => '완료하지 않은 훈련입니다.',
        ],
        'tests' => [
            'completed' => '완료된 진단평가입니다',
            'ticket_purchase_required' => '이용권 구매 후 응시 가능합니다!'
        ]
    ],
];
