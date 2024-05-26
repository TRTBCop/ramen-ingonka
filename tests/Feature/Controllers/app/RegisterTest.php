<?php

use App\Models\Sms;
use App\Models\Student;
use App\Models\StudentPhone;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

test('app.register.create 회원가입폼', function () {
    $this->get(route('app.register.create'))
        ->assertInertia(
            fn (Assert $page) => $page
            ->component('register/Create')
            ->hasAll([
                'referral_info',
                'agree',
                'privacy',
                'marketing'
            ])
        );
})->group('app', 'register');

test('app.register.create 레퍼럴정보(성공)', function () {
    $referralCode = '2023하랑맘2';
    config(['dailykor.service.referrals' => [
        $referralCode => [
            'period' => 3,
            'round' => 3,
            'register_start' => now()->format('Y-m-d 00:00:00'),
            'register_end' => now()->format('Y-m-d 23:59:59'),
            'purchase_start' => now()->format('Y-m-d 00:00:00'),
            'purchase_end' => now()->format('Y-m-d 23:59:59'),
        ]
    ]]);

    $this->get(route('app.register.create', [
        'referral_code' => $referralCode
    ]))
        ->assertInertia(
            fn (Assert $page) => $page
            ->component('register/Create')
            ->where('referral_info', function ($value) {
                return !empty($value);
            })
        );
})->group('app', 'register');

test('app.register.create 레퍼럴정보(실패-없는코드)', function () {
    $referralCode = '2023하랑맘';

    $this->get(route('app.register.create', [
        'referral_code' => $referralCode
    ]))
        ->assertSessionHasNoErrors()
        ->assertSessionHas('message', function ($value) {
            return is_array($value) && in_array('error', $value);
        })
        ->assertRedirectToRoute('brand.index');
})->group('app', 'register');


test('app.register.check-account 아이디 중복 확인 (성공)', function () {
    $response = $this->get(route('app.register.check-account', [
        'access_id' => 'test1234',
    ]));

    $response->assertSuccessful()
        ->assertJsonPath('success', true)
        ->assertJsonPath('message', __('messages.app.students.access_id_available'))
        ->assertJsonStructure([
            'success',
            'data',
        ]);
})->group('app', 'register');


test('app.register.check-account 아이디 중복 확인 (실패)', function () {
    Student::factory()->state([
        'access_id' => 'test1234',
        'password' => bcrypt('1234'),
    ])->create();

    $this->json('GET', route('app.register.check-account', [
        'access_id' => 'test1234',
    ]))->assertSessionHasNoErrors()
        ->assertStatus(422)
        ->assertInvalid(['access_id'])
        ->assertJsonPath('success', false);
})->group('app', 'register');

test('app.register.verification-code-send 휴대폰 인증 번호 발송 (실패-전화번호형식다름)', function () {
    $response = $this->json('POST', route('app.register.verification-code-send'), [
        'phone' => '0100000000004',
    ])->assertSessionHasNoErrors()
        ->assertStatus(422)
        ->assertInvalid(['phone'])
        ->assertJsonPath('success', false);
})->group('app', 'register');


test('app.register.verification-code-send 휴대폰 인증 번호 발송 (성공)', function () {
    Http::fake([
        '221.139.14.136/*' => Http::response(['result' => '100'], 200),
    ]);
    $phone = '01000000000';
    $this->json('POST', route('app.register.verification-code-send'), [
        'phone' => $phone,
    ])->assertSessionHasNoErrors()
        ->assertSuccessful()
        ->assertJsonPath('success', true);

    $this->assertDatabaseHas(StudentPhone::class, [
        'phone' => $phone,
    ]);

    $this->assertDatabaseHas(Sms::class, [
        'dest_phone' => $phone,
    ]);
})->group('app', 'register');

test('app.register.verification-code-check 휴대폰 인증 번호 확인 (성공)', function () {
    $phone = '01000000000';

    // 인증요청
    StudentPhone::create([
        'phone' => $phone,
        'code' => '0000',
        'verified' => 0,
    ]);

    // 인증확인
    $this->json('POST', route('app.register.verification-code-check'), [
        'phone' => $phone,
        'code' => '0000',
    ])->assertSuccessful()
        ->assertJsonPath('success', true)
        ->assertJsonStructure([
            'success',
            'data',
        ]);
})->group('app', 'register');


test('app.register.store 회원가입 (성공)', function () {
    $phone = '01000000000';
    $accessId = 'student1';
    // 인증 결과
    $studentPhone = StudentPhone::create([
        'phone' => $phone,
        'code' => '0000',
        'verified' => 1,
    ]);

    $this->post(route('app.register.store'), [
        'access_id' => $accessId,
        'password' => 'test12!34',
        'c_password' => 'test12!34',
        'student_phone_id' => $studentPhone->id,
        'name' => fake()->name(),
        'phone' => '',
        'birth_date' => date('Y-m-d', strtotime('-20 days')),
        'parents_name' => '학부모',
        'parents_phone' => $phone,
        'marketing_consent' => 1
    ])->assertSessionHasNoErrors()
        ->assertSessionHas('message', function ($value) {
            return is_array($value) && in_array('success', $value);
        });

    $this->assertDatabaseHas('students', [ // 학생등록 체크
        'access_id' => $accessId,
        'parents_phone' => $phone,
        'status' => \App\Enums\StudentStatusEnum::FREE
    ]);
})->group('app', 'register');

test('app.register.store 회원가입결과', function () {
    $student = Student::factory()->state([
        'phone' => '01000000000',
    ])->create();

    $cryptStudentId = Crypt::encrypt($student->id);


    $this->get(route('app.register.result', [
        'id' => $cryptStudentId,
    ]))->assertInertia(
        fn (Assert $page) => $page
        ->component('register/Result')
        ->hasAll(['name', 'access_id', 'free_trial_period'])
        ->etc()
    );
})->group('app', 'register');

test('app.register.find-account 아이디찾기', function () {
    $this->get(route('app.register.find-account'))
        ->assertInertia(
            fn (Assert $page) => $page
            ->component('register/FindAccount')
        );
})->group('app', 'register');

test('app.register.find-account.store 아이디찾기결과-실패', function () {
    $parentsPhone = '01000000000';
    $name = fake()->name();


    $this->post(route('app.register.find-account.store', [
        'name' => $name,
        'parents_phone' => $parentsPhone,
    ]))
        ->assertInertia(
            fn (Assert $page) => $page
            ->component('register/FindAccountResult')
            ->where('student_id', '')
            ->where('access_id', '')
            ->where('created_date', '')
        );
})->group('app', 'register');


test('app.register.find-account.store 아이디찾기결과-성공', function () {
    $parentsPhone = '01000000000';
    $name = fake()->name();

    $student = Student::factory()->state([
        'access_id' => '1234567890',
        'name' => $name,
        'parents_phone' => $parentsPhone,
        'created_at' => '2023-11-07 00:00:05'
    ])->create();


    $this->post(route('app.register.find-account.store', [
        'name' => $name,
        'parents_phone' => $parentsPhone,
    ]))
        ->assertInertia(
            fn (Assert $page) => $page
            ->component('register/FindAccountResult')
            ->where('student_id', 1)
            ->where('access_id', '1234567890')
            ->where('created_date', '2023-11-07')
        );
})->group('app', 'register');

test('app.register.find-password 비밀번호찾기', function () {
    $this->get(route('app.register.find-password'))
        ->assertInertia(
            fn (Assert $page) => $page
            ->component('register/FindPassword')
        );
})->group('app', 'register');

test('app.register.find-password.store 비밀번호찾기 처리', function () {
    $parentsPhone = '01000000000';
    $name = fake()->name();

    $student = Student::factory()->state([
        'access_id' => '1234567890',
        'name' => $name,
        'parents_phone' => $parentsPhone,
        'created_at' => '2023-11-07 00:00:05'
    ])->create();

    $this->post(route('app.register.find-password.store'), [
        'access_id' => $student->access_id,
        'name' => $name,
        'parents_phone' => $parentsPhone,
    ])->assertInertia(
        fn (Assert $page) => $page
        ->component('register/FindPasswordReset')
        ->where('access_id', $student->access_id)
        ->where('created_date', $student->created_at->format('Y-m-d'))
        ->where('parents_phone', $student->parents_phone)
    );
})->group('app', 'register');

test('app.register.find-password.store 비밀번호찾기 리셋', function () {
    $parentsPhone = '01000000000';
    $name = fake()->name();
    $newPassword = 'ABCD1234!';
    $student = Student::factory()->state([
        'access_id' => '1234567890',
        'name' => $name,
        'parents_phone' => $parentsPhone,
        'created_at' => '2023-11-07 00:00:05'
    ])->create();

    // 인증요청
    $studentPhone = StudentPhone::create([
        'phone' => $parentsPhone,
        'code' => '0000',
        'verified' => 1,
    ]);

    $this->post(route('app.register.find-password-reset'), [
        'student_id' => $student->id,
        'student_phone_id' => $studentPhone->id,
        'password' => $newPassword,
        'c_password' => $newPassword,
    ])->assertInertia(
        fn (Assert $page) => $page
        ->component('register/FindPasswordResult')
        ->where('access_id', $student->access_id)
        ->where('created_date', $student->created_at->format('Y-m-d'))
    );

    // 비밀번호 변경확인
    $student->refresh();
    $this->assertTrue(Hash::check($newPassword, $student->password));
})->group('app', 'register');
