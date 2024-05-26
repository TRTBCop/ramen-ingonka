<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PaymentCashReceiptEnum;
use App\Enums\PaymentMethodEnum;
use App\Enums\PaymentStatusEnum;
use App\Enums\StudentStatusEnum;
use App\Enums\TransactionTypeEnum;
use App\Exports\PaymentExport;
use App\Http\Controllers\BaseController;
use App\Http\Requests\PaymentRequest;
use App\Models\Academy;
use App\Models\Payment;
use App\Models\Student;
use App\Services\PaymentService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rules\Enum;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;

class PaymentController extends BaseController
{
    public function __construct(
        public string $name = '결제',
    ) {
        $this->middleware(['permission:academy']);
    }

    /*
     * GET|HEAD | admin/b2c-payments | admin.b2c-payments.index
     */
    public function b2cIndex(Request $request): Response
    {
        $request->merge([
            'is_b2c' => true,
        ]);
        return $this->index();
    }

    /**
     * GET|HEAD | admin/students/{student}/payments | admin.students.payments.index
     */
    public function studentIndex(Request $request, Student $student): Response
    {
        $request->merge([
            'student_id' => $student->id,
        ]);

        $component = 'student/payments/Index';
        $breadcrumbs = '학생관리';
        $active = 'admin.students.index';

        $extra = [
            'student' => $student->append(['avatar']),
        ];

        $collection = $this->getCollection(Payment::class);

        $collection->each(function (&$value) {
            $pgResultFirst = $value->extra['pg_result'][0];

            $value['cash_receipt'] = false;
            if ($value->method->value == 'VA') {
                if (isset($value->extra['cash_receipt'])) {
                    $value['cash_receipt'] = true;
                }
                $detail = [
                    $pgResultFirst['bankNm'],
                    $pgResultFirst['vAcntNo'],
                ];
            } elseif ($value->method->value == 'CA') {
                $detail = [
                    $pgResultFirst['cardNm'],
                    $pgResultFirst['cardNo'],
                ];
            } else {
                $arrName = ['KKP' => '카카오페이', 'NVP' => '네이버페이', 'PAC' => '페이코'];
                $detail = [
                    $arrName[$pgResultFirst['ezpDivCd']],
                ];
                $value['txt_method'] = $arrName[$pgResultFirst['ezpDivCd']];
            }

            $value['info'] = [
                'type' => $value->txt_method,
                'detail' => $detail,
                'trd_no' => $pgResultFirst['trdNo'],
            ];
            $value['model'] = $value->model;
        });

        return Inertia::render($component, [
                'academies' => Academy::all()->pluck('name', 'id'),
                'collection' => $collection,
                'student_id' => $student->id,
                'route_name' => request()->route()->getName(),
                'config' => [
                    'dbcode' => [
                        'students' => config('dailykor.dbcode.students'),
                        'payments' => config('dailykor.dbcode.payments'),
                    ],
                ],
                'page' => [
                    'active' => $active,
                    'title' => '결제내역',
                    'breadcrumbs' => [$breadcrumbs],
                ],
            ] + $extra);
    }

    /**
     * GET|HEAD | admin/students/{student}/payments/{payment} | admin.students.payments.show
     */
    public function studentShow(Request $request, Student $student, Payment $payment): Response
    {
        $request->merge([
            'student_id' => $student->id,
        ]);

        $component = 'student/Show';
        $breadcrumbs = '학생관리';
        $active = 'admin.students.index';

        $extra = [
            'student' => $student->append(['avatar']),
        ];

        return Inertia::render($component, [
                'academies' => Academy::all()->pluck('name', 'id'),
                'payment' => $payment,
                'config' => [
                    'dbcode' => [
                        'students' => config('dailykor.dbcode.students'),
                        'payments' => config('dailykor.dbcode.payments'),
                    ],
                ],
                'page' => [
                    'active' => $active,
                    'title' => '결제내역',
                    'breadcrumbs' => [$breadcrumbs],
                ],
            ] + $extra);
    }

    /**
     * b2c 결제 목록 다운로드
     * GET|HEAD | admin/b2c-payments-export | admin.b2c-payments.export
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function b2cExport(): BinaryFileResponse
    {
        request()->merge([
            'is_b2c' => true,
        ]);
        return $this->export();
    }


    /**
     * 결제내역
     *
     * GET|HEAD | admin/b2c-payments | admin.b2c-payments.index
     * GET|HEAD | admin/academies/{academy}/payments | admin.academies.payments.index
     * GET|HEAD | admin/payments | admin.payments.index
     */
    public function index(Academy $academy = null): Response
    {
        $routeName = request()->route()->getName();
        $component = 'payments/Index';
        $breadcrumbs = '결제관리';
        $extra = [];
        $active = 'admin.payments.index';

        $collection = $this->getCollection(Payment::class);

        $collection->each(function (&$value) {
            $pgResultFirst = $value->extra['pg_result'][0];

            $value['cash_receipt'] = false;
            if ($value->method->value == 'VA') {
                if (isset($value->extra['cash_receipt'])) {
                    $value['cash_receipt'] = true;
                }
                $detail = [
                    $pgResultFirst['bankNm'],
                    $pgResultFirst['vAcntNo'],
                ];
            } elseif ($value->method->value == 'CA') {
                $detail = [
                    $pgResultFirst['cardNm'],
                    $pgResultFirst['cardNo'],
                ];
            } else {
                $arrName = ['KKP' => '카카오페이', 'NVP' => '네이버페이', 'PAC' => '페이코'];
                $detail = [
                    $arrName[$pgResultFirst['ezpDivCd']],
                ];
                $value['txt_method'] = $arrName[$pgResultFirst['ezpDivCd']];
            }

            $value['info'] = [
                'type' => $value->txt_method,
                'detail' => $detail,
                'trd_no' => $pgResultFirst['trdNo'],
            ];
            $value['model'] = $value->model;
        });

        return Inertia::render($component, [
                'academies' => Academy::all()->pluck('name', 'id'),
                'collection' => $collection,
                'route_name' => $routeName,
                'config' => [
                    'dbcode' => [
                        'students' => config('dailykor.dbcode.students'),
                        'payments' => config('dailykor.dbcode.payments'),
                    ],
                    'bank' => config('dailykor.code.bank'),
                ],
                'page' => [
                    'active' => $active,
                    'title' => '결제내역',
                    'breadcrumbs' => [$breadcrumbs],
                ],
            ] + $extra);
    }

    /**
     * 액셀다운로드
     *
     * GET|HEAD | admin/b2c-payments-export | admin.b2c-payments.export
     * GET|HEAD | admin/payments-export | admin.payments.export
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function export(): BinaryFileResponse
    {
        return Excel::download(new PaymentExport(), '결제목록 '.now()->format('Y-m-d').'.xlsx');
    }

    /**
     * 결제 직접등록
     * GET|HEAD | admin/payments/create | admin.payments.create
     */
    public function create(): Response
    {
        return Inertia::render('payments/Create', [
            'academies' => Academy::all()->pluck('name', 'id'),
            'config' => [
                'dbcode' => [
                    'payments' => config('dailykor.dbcode.payments'),
                ],
            ],
            'page' => [
                'active' => 'admin.academies.index',
                'title' => '결제생성',
                'breadcrumbs' => ['결제관리'],
            ],
        ]);
    }

    /**
     * 결제 직접등록 action
     * POST | admin/payments | admin.payments.store
     */
    public function store(PaymentRequest $request): RedirectResponse
    {
        $payment = new Payment();
        $academy = Academy::find($request->academy_id);
        if (!$academy) {
            return redirect()->back()->with('message', ['error', __('messages.invalid_request')]);
        }

        $input = $request->all();
        $input['method'] = 'B'; // 무통장
        $input['order_id'] = (new PaymentService())->genOrderId();
        $input['order_name'] = request()->order_name ?: $academy->name;
        $input['model_type'] = Academy::class;
        $input['model_id'] = $academy->id;
        if ($input['approved_at']) {
            $input['status'] = PaymentStatusEnum::APPROVE;
        } else {
            unset($input['approved_at']);
        }

        $payment->fill($input)->setActivitylogOptions([
            'description' => $this->name.'가 등록되었습니다.',
        ])->save();

        // 포인트 지급
        if (isset($input['approved_at']) && $input['approved_at']) {
            try {
                $academy->setTransaction([
                    'payment_id' => $payment->id,
                    'amount' => $input['amount'],
                    'type' => TransactionTypeEnum::CHARGE_BANK->value,
                ]);
            } catch (Throwable $e) {
                return redirect()->back()->with('message', [
                    'error',
                    __('messages.admin.payments.fail_transaction'),
                    $e->getMessage(),
                ]);
            }
        }


        return to_route('admin.payments.index')->with('message', ['success', '저장성공']);
    }


    /**
     * 수동입금확인
     * PATCH | admin/payments/{payment}/confirmation| admin.payments.confirmation
     */
    public function confirmation(Payment $payment): RedirectResponse
    {
        $payment->status = PaymentStatusEnum::APPROVE;
        $payment->approved_at = now();
        $payment->setActivityLogOptions([
            'description' => '결제삭제',
        ])->save();
        try {
            $payment->model->setTransaction([
                'payment_id' => $payment->id,
                'amount' => $payment->amount,
                'type' => TransactionTypeEnum::CHARGE_BANK->value,
            ]);
            $message = ['success', '입금 완료 처리 성공'];
        } catch (Throwable $e) {
            $message = [
                'error',
                __('messages.admin.payments.fail_transaction'),
                $e->getMessage(),
            ];
        }

        return back()->with('message', $message);
    }

    /**
     * 현금영수증 발행
     * POST | admin/payments/cash-receipt/{payment} | admin.payments.cash-receipt
     * @param Payment $payment
     * @return JsonResponse|RedirectResponse
     */
    public function cashReceipt(Payment $payment): JsonResponse|RedirectResponse
    {
        $paymentService = new PaymentService();
        try {
            request()->validate([
                'identity_gb' => ['required', new Enum(PaymentCashReceiptEnum::class)],
                'identity' => 'required',
            ]);

            $result = $paymentService->cashReceipt($payment, request()->identity_gb, request()->identity)->json();

            if ($result['resultCd'] != '0000') {
                throw new Exception($result['resultMsg']);
            }

            // 요청 성공시 기록
            $payment->extra += [
                'cash_receipt' => [ // 현금영수증
                    'published_at' => now()->format('Y-m-d H:i:s'), // 발행유무
                    'identity' => request()->identity, // 휴대폰번호 or 사업자번호
                    'type' => request()->identity_gb,
                    'auth_code' => $result['authNo'],
                ],
            ];

            $payment->save();

            return back()->with('message', ['success', '현금영수증발행 성공']);
        } catch (Exception $e) {
            return back()->with('message', ['error', '현금영수증발행 실패', $e->getMessage()]);
        }
    }

    /**
     * 결제취소
     * POST | admin/payments/{payment}/cancel | admin.payments.cancel
     *
     * @param Payment $payment
     * @return RedirectResponse
     * @throws Throwable
     */
    public function cancel(PaymentRequest $request, Payment $payment): RedirectResponse
    {
        $request->validate($request->cancelValidate());

        $products = config('dailykor.payment.products');

        $mchtParam = json_decode(str_replace('\'', '"', $payment->extra['pg_result'][0]['mchtParam'])); // 상점예약정보

        $arrData['mchtId'] = $payment->amount;
        $arrData['od_id'] = $payment->od_id;
        $arrData['method'] = $payment->code_method;
        $arrData['trd_no'] = $payment->trd_no;
        $arrData['amount'] = $request->amount;
        $arrData['cancel_order'] = '001';
        //가상계좌 환불시 필요정보
        $arrData['refundBankCd'] = $request->refundBankCd;
        $arrData['refundAcntNo'] = $request->refundAcntNo;
        $arrData['refundDpstrNm'] = $request->refundDpstrNm;

        $res = (new PaymentService())->cancel($arrData);    // 취소 처리

        if (!$res['success']) {
            throw new Exception($res['message'], 400);
        }

        $status = PaymentStatusEnum::CANCEL;
        // 가격 체크후 취소상태
        if ($request->amount < $payment->amount) {
            $status = PaymentStatusEnum::PARTIAL_CANCEL;
        }

        $payment->setActivitylogOptions([
            'description' => '이용권 결제 취소',
            'is_show' => 1,
        ])->update([
            'status' => $status,
            'cancel_amount' => $request->amount,
            'canceled_at' => now(),
        ]);

        if ($payment->model_type == Student::class) {
            $user = Student::find($payment->model_id);

            $serviceDay = $products[$mchtParam->product_code]['day'];

            // 서비스 종료일 차감
            $newEndDate = $user->service_end_date->subDays($serviceDay);

            $user->service_end_date = $newEndDate;

            if (now()->gt($newEndDate)) {
                // 오늘 날짜가 차감된 종료일을 넘겼을 경우 서비스 종료
                $user->status = StudentStatusEnum::EXPIRED;
            }

            $user->setActivitylogOptions([
                'description' => '이용권 취소로 인한 이용기간 변경',
                'is_show' => 1,
            ])->save();
        }

        return back()->with('message', ['success', '결제취소성공']);
    }
}
