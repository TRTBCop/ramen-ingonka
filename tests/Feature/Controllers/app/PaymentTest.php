<?php

use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

function fixture(string $key)
{
    return json_decode(file_get_contents(base_path('tests/Fixtures/payment.json')), true)[$key];
}

uses(RefreshDatabase::class)->beforeEach(function () {
    Artisan::call('db:seed');

    $this->student = Student::factory()->state([
        'access_id' => 'test@naver.com',
        'password' => bcrypt('1234'),
        'grade' => 3,
        'term' => 1,
    ])->create();
});

test('app.payments.noti 이용권 카드결제 (성공)', function () {
    $dataset = fixture('cardApprove');
    $response = $this->post(route('app.payments.noti', [
        'studentId' => 1,
    ]), $dataset);
    $response->dump();
    $response->assertSuccessful()
        ->assertSee('OK');
})->group('payments');

test('app.payments.noti 이용권 가상계좌 발급 (성공)', function () {
    $dataset = fixture('vbankWaiting');
    $response = $this->actingAs($this->student)->post(route('app.payments.noti', [
        'studentId' => 1,
    ]), $dataset);
    $response->assertSuccessful()
        ->assertSee('OK');
})->group('payments');

test('app.payments.noti 이용권 가상계좌 입금 (성공)', function () {
    $dataset = fixture('vbankWaiting');
    $datasetApprove = fixture('vbankApprove');
    $this->actingAs($this->student)->post(route('app.payments.noti', [
        'studentId' => 1,
    ]), $dataset); // 가상계좌 생성

    $response = $this->post(route('app.payments.noti', [
        'studentId' => $this->student->id,
    ]), [...$datasetApprove])
        ->assertSuccessful()
        ->assertSee('OK');
})->group('payments');

test('app.payments.index 이용권 결제 목록 (성공)', function () {
    $this->post(route('app.payments.noti', [
        'studentId' => $this->student->id,
    ]), fixture('vbankApprove')); // 가상계좌 생성
    $this->post(route('app.payments.noti', [
        'studentId' => $this->student->id,
    ]), fixture('cardApprove')); // 카드 결제

    $this->actingAs($this->student)->get(route('app.payments.list'))
        ->assertInertia(fn (Assert $page) => $page
        ->component('payments/Index')
        ->has('collection')
    );
})->group('payments');

test('app.payments.show 이용권 결제 상세 (성공)', function () {
    $this->post(route('app.payments.noti', [
        'studentId' => $this->student->id,
    ]), fixture('vbankApprove')); // 가상계좌 생성

    $this->actingAs($this->student)->get(route('app.payments.show', [
        'orderId' => $this->student->id
    ]))
        ->assertInertia(fn (Assert $page) => $page
            ->component('payments/Show')
            ->has('payment', fn (Assert $page) => $page
                ->hasAll(['id', 'od_id', 'amount', 'status', 'method', 'extra', 'txt_status', 'txt_method'])
                ->etc()
            )
            ->where('route_name', 'app.payments.show')
        );
})->group('payments');