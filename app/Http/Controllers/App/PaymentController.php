<?php

namespace App\Http\Controllers\App;

use App\Enums\PaymentStatusEnum;
use App\Enums\StudentStatusEnum;
use App\Http\Controllers\BaseController;
use App\Http\Requests\PaymentRequest;
use App\Models\Payment;
use App\Models\Student;
use App\Services\PaymentService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;

class PaymentController extends BaseController
{
    public function __construct()
    {
    }


    /**
     * 이용권 결제 화면
     *
     * GET | app/payments/create | app.payments.create
     * @return Response
     */
    public function create(): Response
    {
        $products = config('dailykor.payment.products');

        return Inertia::render('payments/Create', [
            'products' => $products,
            'config' => [
                'payment' => [
                    'pg' => (new PaymentService())->config('pg'),
                ],
            ]
        ]);
    }

    /**
     * 이용권 결제 후 결과 화면
     *
     * GET | app/payments/{orderId}/result | app.payments.result
     * @return JsonResponse|Response
     */
    public function result(PaymentRequest $request): JsonResponse|Response
    {
        $request->validate($request->resultValidate());

        $input = $request->all();

        return Inertia::render('payments/Result', [
            'params' => $input,
            'config' => [
                'payment' => [
                    'products' => config('dailykor.payment.products'),
                ],
            ],
            'route_name' => request()->route()->getName(),
        ]);
    }

    /**
     * 현금영수증 발급
     *
     * POST | app/payments/issue-cash-receipt | app.payments.issue-cash-receipt
     * @param PaymentRequest $request
     * @return JsonResponse
     */
    public function issueCashReceipt(PaymentRequest $request): JsonResponse
    {
        $request->validate($request->issueCashReceiptValidate());

        try {
            $payment = auth()->user()->payments()->where('od_id', $request->od_id)->firstOrFail();

            $res = (new PaymentService())->cashReceipt($payment, $request->identity_gb, $request->identity);

            $extra['cash_receipt'] = [
                'no' => $res['authNo'] ?? '',
                'identity' => $request->identity,
                'identity_gb' => $request->identity_gb,
                'pg_response' => $res,     // pg사 결과 저장
            ];

            $payment->extra = $extra;
            $payment->save();

            if ($res['resultCd'] != '0000') {
                throw new \Exception($res['resultMsg'], 403);
            }

            return $this->sendResponse($res, '현금영수증 등록 성공');
        } catch (\Exception $e) {
            return $this->sendError('현금영수증 등록 실패', $e->getMessage(), $e->getCode());
        }
    }

    /**
     * 이용권 주문 취소
     *
     * PUT | app/payments/cancel | app.payments.cancel
     * @param Payment $payment
     * @return JsonResponse
     */
    public function cancel(Payment $payment): JsonResponse
    {
        $arrData = [];
        $products = config('dailykor.payment.products');

        try {
            $user = auth()->user();

            if (!$user instanceof Student) {
                throw new \Exception('잘못된 접근입니다.', 403);
            }

            $mchtParam = json_decode(str_replace('\'', '"', $payment->extra['pg_result'][0]['mchtParam'])); // 상점예약정보

            $arrData['mchtId'] = $payment->amount;
            $arrData['od_id'] = $payment->od_id;
            $arrData['method'] = $payment->code_method;
            $arrData['trd_no'] = $payment->trd_no;
            $arrData['amount'] = $payment->amount;
            $arrData['cancel_order'] = '001';

            $res = (new PaymentService())->cancel($arrData);    // 취소 처리

            if (!$res['success']) {
                throw new \Exception($res['message'], 400);
            }

            $payment->setActivitylogOptions([
                'description' => '이용권 결제 취소',
                'is_show' => 1,
            ])->update([
                'status' => PaymentStatusEnum::CANCEL,
                'canceled_at' => now(),
            ]);

            $serviceDay = $products[$mchtParam->product_code]['day'];

            // 서비스 종료일 차감
            $previousEndDate = Carbon::createFromFormat('Y-m-d', $user->service_end_date);
            $newEndDate = $previousEndDate->subDays($serviceDay)->hour(23)->minute(59)->second(59);

            $user->service_end_date = $newEndDate;

            if (now()->gt($newEndDate)) {
                // 오늘 날짜가 차감된 종료일을 넘겼을 경우 서비스 종료
                $user->status = StudentStatusEnum::EXPIRED;
            }

            $user->setActivitylogOptions([
                'description' => '이용권 취소로 인한 이용기간 변경',
                'is_show' => 1,
            ])->save();

            return $this->sendResponse($res, '결제 취소 성공');
        } catch (\Exception $e) {
            return $this->sendError('결제 취소 실패', $e->getMessage(), $e->getCode());
        }
    }

    /**
     * 해쉬 생성
     *
     * POST | app/payments/encrypt-params | app.payments.encrypt-params
     * @return JsonResponse
     */
    public function encryptParams(): JsonResponse
    {
        try {
            $paymentService = new PaymentService();
            return $this->sendResponse($paymentService->encryptParams(), 'hash 생성 성공');
        } catch (\Exception $e) {
            return $this->sendError(
                'hash 생성 실패',
                $e->getMessage(),
                $e->getCode()
            );
        }
    }

    /**
     * pg 결과 db 처리 (학생전용)
     *
     * POST | app/payment/noti/{studentId} | app.payment.noti
     * @param number $studentId
     * @return string
     */
    public function noti($studentId): string
    {
        $paymentService = new PaymentService();
        $student = Student::find($studentId);

        try {
            if (!$student) {
                $paymentService->log->info(__METHOD__.'-학생정보 없음', request()->all());
                throw new \Exception();
            }

            if (!$paymentService->noti($student)) {
                throw new \Exception();
            }

            return 'OK';
        } catch (\Exception $e) {
            return 'FAIL';
        }
    }

    /**
     * 세틀뱅크 팝업 결제 완료 후 결과 처리 페이지
     *
     *  POST | app/payment/next | app.payment.next
     * @return Response
     */
    public function next(): Response
    {
        $responseParams = (new PaymentService())->payReceiveResult();

        return Inertia::render('payments/Next', [
            'responseParams' => $responseParams,
            'route_name' => request()->route()->getName(),
        ]);
    }

    /**
     * 세틀뱅크 팝업 결제 취소 후 결과 처리 페이지
     *
     * POST | app/payment/canc | app.payment.canc
     * @return Response
     */
    public function canc(): Response
    {
        $resParams = (new PaymentService())->payReceiveResult();

        return Inertia::render('payments/Cancel', [
            'collection' => compact(['resParams']),
            'route_name' => request()->route()->getName(),
        ]);
    }
}
