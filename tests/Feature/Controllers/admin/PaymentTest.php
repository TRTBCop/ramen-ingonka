<?php

namespace Admin;

use App\Enums\PaymentStatusEnum;
use App\Models\Admin;
use App\Models\Payment;
use App\Models\Student;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class)->beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
    $this->admin = Admin::factory()->create()->assignRole('super');
});


test('admin.students.payments.index 학생개별결제목록', function () {
    $student = Student::factory()->create();
    Payment::factory(10)->state([
        'model_type' => Student::class,
    ])->create();
    Payment::factory(10)->state([
        'model_type' => Student::class,
        'model_id' => $student->id,
    ])->create();

    $this->actingAs($this->admin, 'admin')->get(route('admin.students.payments.index', $student))
        ->assertInertia(
            fn (Assert $page) => $page
            ->component('student/payments/Index')
            ->where('collection.meta.total', 10)
            ->has('page')
        );
})->group('admin', 'students', 'payments');



test('admin.payments.destroy 삭제', function () {
    $payment = Payment::factory()->create();

    $this->actingAs($this->admin, 'admin')->delete(route('admin.payments.destroy', $payment))
        ->assertSessionHasNoErrors()
        ->assertSessionHas('message', function ($value) {
            return is_array($value) && in_array('success', $value);
        });

    $this->assertSoftDeleted($payment);
})->group('admin', 'payments');



test('admin.payments.cash-receipt 현금영수증발행', function () {
    $payment = Payment::factory()->state([
        'amount' => 10000,
        'extra' => [
            'aaa' => '111',
        ],
    ])->create();
    $this->actingAs($this->admin, 'admin')->post(route('admin.payments.cash-receipt', $payment), [
        'identity_gb' => 4,
        'identity' => '00050665355',
    ])
        ->assertSessionHasNoErrors()
        ->assertSessionHas('message', function ($value) {
            return is_array($value) && in_array('success', $value);
        });
})->group('admin', 'payments');


test('admin.payments.cancel 결제취소', function () {
    $student = Student::factory()->create();
    $payment = Payment::factory()->state([
        'amount' => 3000,
        'od_id' => 'S202307141155592122',
        'od_name' => fake()->name,
        'model_type' => Student::class,
        'model_id' => $student->id,
        'trd_no' => 'STFP_PGCAnxca_jt_il0230714115600M1502303',
        'extra' => [
            'pg_result' => [
                json_decode('{"outStatCd":"0021","trdNo":"STFP_PGCAnxca_jt_il0230714115600M1502303","method":"CA","bizType":"B0","mchtId":"nxca_jt_il","mchtTrdNo":"S202307141155592122","mchtCustNm":"홍길동","mchtName":"세틀뱅크","pmtprdNm":"테스트상품","trdDtm":"20230714115657","trdAmt":"300","cardCd":"HDC","cardNm":"현대개인일반카드","email":"HongGilDong@example.com","mchtCustId":"HongGilDong","cardNo":"949019******0402","cardApprNo":"99116722","instmtMon":"00","mchtParam":"{\'product_code\':\'A01\'}","pktHash":"111cdfd7d020d2629f874421badde1953868f5d22c7d26f4cbd21d8cd2be4010"}', true),
            ],
        ],
        'status' => PaymentStatusEnum::APPROVE,
    ])->create();

    $this->actingAs($this->admin, 'admin')->post(route('admin.payments.cancel', $payment), [
        'amount' => 3000,
    ])
        ->assertSessionHasNoErrors()
        ->assertSessionHas('message', function ($value) {
            return is_array($value) && in_array('success', $value);
        });

    $payment->refresh();
    $this->assertSame(PaymentStatusEnum::CANCEL, $payment->status);
})->group('admin', 'payments');
