<?php

namespace App\Services;

use App\Enums\PaymentMethodEnum;
use App\Enums\PaymentStatusEnum;
use App\Models\Academy;
use App\Models\Payment;
use App\Models\Student;
use App\Notifications\ServicePaymentCompletedNotification;
use DB;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Validation\Rules\Enum;
use Psr\log\LoggerInterface;
use Spatie\Activitylog\Facades\LogBatch;
use Throwable;

class PaymentService
{
    public const VER = '0A19';
    public const ENCCD = '23';

    // 결제취소 URL
    public const CANCEL_URL = '/spay/APICancel.do';  // 신용카드 취소
    public const VBANK_CANCEL_URL = '/spay/APIRefund.do'; //가상계좌 환불


    public LoggerInterface $log;
    private array $paymentConfig;
    private string $aesKey;

    public function __construct()
    {
        $this->paymentConfig = config('dailykor.payment');
        $this->aesKey = $this->config('pg.aes256_key');

        $this->log = Log::build([
            'driver' => 'single',
            'path' => storage_path('logs/payments/.'.now()->format('Ymd').'.log'),
        ]);
    }

    public function config($dotPath)
    {
        $env = app()->isProduction() ? 'production' : 'development';
        $config = $this->paymentConfig;
        $config['pg'] = $this->paymentConfig['pg'][$env];

        return Arr::get($config, $dotPath);
    }

    /**
     * 사용자 IP 정보
     */
    public function getRealClientIp(): string
    {
        if (getenv('HTTP_CLIENT_IP')) {
            $ipaddress = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_X_FORWARDED')) {
            $ipaddress = getenv('HTTP_X_FORWARDED');
        } elseif (getenv('HTTP_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        } elseif (getenv('HTTP_FORWARDED')) {
            $ipaddress = getenv('HTTP_FORWARDED');
        } elseif (getenv('REMOTE_ADDR')) {
            $ipaddress = getenv('REMOTE_ADDR');
        } else {
            $ipaddress = '';
        }

        return $ipaddress;
    }

    /**
     * 결제 등록/수정
     * @param Student|Academy $model
     * @param $type
     * @return Payment
     * @throws Exception
     */
    public function setPayment(Student|Academy $model, $type): Payment
    {
        request()->all();
        $mchtParam = json_decode(str_replace('\'', '"', request()->mchtParam)); // 상점예약정보

        if ($type == 'deposit') {
            /** 가상계좌 입금 처리 */
            $payment = Payment::where([
                'od_id' => request()->mchtTrdNo,
                'status' => PaymentStatusEnum::WAITING,
            ])->first(); // 가상계좌 입금 대기건을 찾는다

            if (!$payment) {
                throw new Exception('입금대기중인 거래내역이 없습니다.');
            }

            if ($payment->amount != request()->trdAmt) {
                throw new Exception('결제 금액이 상이합니다.');
            }

            $tempSet = [
                'status' => PaymentStatusEnum::APPROVE, // 결제 완료
                'approved_at' => now(),
            ];
            $extra = $payment->extra;
            $extra['pg_result'][] = request()->all();

            if (isset(request()->csrcIssNo)) {
                // 현금영수증 신청한 경우
                $extra['cash_receipt'] = [ // 현금영수증 정보
                    'no' => request()->csrcIssNo,    // PG사에서 넘어온 현금영수증 승인 번호
                ];
            }

            $description = '정상결제되었습니다';
        } elseif ($type == 'cancel') {
            /** 결제취소 */
            $payment = Payment::where([
                'od_id' => request()->mchtTrdNo,
                'status' => PaymentStatusEnum::CANCEL,
            ])->first(); // 가상계좌 입금 대기건을 찾는다

            if (!$payment) {
                throw new Exception('거래내역이 없습니다.');
            }

            $tempSet = [];
            $extra = $payment->extra;
            $extra['pg_result'][] = request()->all();

            $description = '결제가 취소되었습니다.';
        } else {
            /** 카드결제 및 가상계좌 발급 */
            $payment = new Payment();
            $tempSet = [
                'amount' => request()->trdAmt,
                'od_id' => request()->mchtTrdNo,
                'od_name' => request()->mchtCustNm ?? '',
                'trd_no' => request()->trdNo,
                'method' => request('method'),
                'model_type' => $model::class,
                'model_id' => $model->id,
                'status' => PaymentStatusEnum::APPROVE,
                'approved_at' => now(),
            ];

            if (isset($mchtParam->product_code)) {
                $tempSet['product_code'] = $mchtParam->product_code;
            }

            $extra = [
                'pg_result' => [request()->all()],
            ];
            $description = '결제 정상결제되었습니다.';

            if ($type == 'account') {
                // 가상계좌 발급
                unset($tempSet['approved_at']);
                $tempSet['status'] = PaymentStatusEnum::WAITING; // 입금 대기
                $extra['vbank'] = [ // 가상계좌 정보
                    'account_num' => request()->vAcntNo, // 입금계좌
                    'expiration_date' => now()->parse(request()->expireDt)->format('Y-m-d H:i:s'), // 만료일
                    'bank_code' => request()->bankCd,
                    'bank_name' => request()->bankNm,
                ];
                $description = '결제 등록되었습니다.(입금대기)';
            }
        }

        $set = [
            ...$tempSet,
            'extra' => $extra,
        ];

        $payment->fill($set)->setActivitylogOptions([
            'description' => $description,
            'is_show' => 1,
        ])->save();

        return $payment;
    }

    /**
     * ui 에서 데이터 전달전 aes256 암호화 진행
     * @return array
     */
    public function encryptParams(): array
    {
        /** 설정 정보 얻기 */
        $licenseKey = $this->config('pg.license_key');

        $input = request()->all();

        // null to string
        $input = array_map(function ($v) {
            return $v ?? '';
        }, $input);

        $orderId = $this->genOrderId();

        /** SHA256 해쉬 파라미터 */
        $mchtId = $input['mchtId'];
        $method = $input['method'];
        $trdDt = $input['trdDt'];
        $trdTm = $input['trdTm'];
        $trdAmt = $input['plainTrdAmt'];

        /** AES256 암호화 파라미터 */
        $params = [
            'trdAmt' => $trdAmt,
            'mchtCustNm' => ($input['plainMchtCustNm'] ?? ''),
            'cphoneNo' => ($input['plainCphoneNo'] ?? ''),
            'email' => ($input['plainEmail'] ?? ''),
            'mchtCustId' => ($input['plainMchtCustId'] ?? ''),
            'taxAmt' => ($input['plainTaxAmt'] ?? ''),
            'vatAmt' => ($input['plainVatAmt'] ?? ''),
            'taxFreeAmt' => ($input['plainTaxFreeAmt'] ?? ''),
            'svcAmt' => ($input['plainSvcAmt'] ?? ''),
            'clipCustNm' => ($input['plainClipCustNm'] ?? ''),
            'clipCustCi' => ($input['plainClipCustCi'] ?? ''),
            'clipCustPhoneNo' => $input['plainClipCustPhoneNo'],
        ];

        /*
         * SHA256 해쉬 처리
         * 조합 필드 : 상점아이디 + 결제수단 + 상점주문번호 + 요청일자 + 요청시간 + 거래금액(평문) + 라이센스키
        */
        $hashPlain = $mchtId.$method.$orderId.$trdDt.$trdTm.$trdAmt.$licenseKey;
        $hashCipher = hash('sha256', $hashPlain);//해쉬 값

        foreach ($params as $key => $value) {
            if (!$value) {
                continue;
            }

            $chiperRaw = openssl_encrypt($value, 'AES-256-ECB', $this->aesKey, OPENSSL_RAW_DATA);
            $aesCipher = base64_encode($chiperRaw);
            $params[$key] = $aesCipher;//암호화된 데이터로 세팅
        }

        return [
            'hash_cipher' => $hashCipher,
            'enc_params' => $params,
            'od_id' => $orderId,
        ];
    }


    /**
     * db 처리
     * @throws Exception
     */
    public function noti($model): bool
    {
        /** 설정 정보 저장 */
        $licenseKey = $this->config('pg.license_key');

        /** 노티 처리 결과 */
        $input = request()->all();
        $this->log->info('receiveNoti :: ', $input);

        /** 해쉬 조합 필드
         *  결과코드 +  거래일시 + 상점아이디 + 가맹점거래번호 + 거래금액 + 라이센스키 */
        $hashPlain = $input['outStatCd'].$input['trdDtm'].$input['mchtId'].$input['mchtTrdNo'].$input['trdAmt'].$licenseKey;

        /** SHA256 해쉬 처리 */
        $hashCipher = '';
        try {
            $hashCipher = hash('sha256', $hashPlain);//해쉬 값
        } catch (Exception $e) {
            $this->log->info($ex->getMessage());
        }

        $arrNotify = [
            'type' => '',
            'params' => [],
        ];
        $class = $this; // transaction 내부에서 $this 사용을 위한 할당
        $resp = false;

        /**
         * hash데이타값이 맞는 지 확인 하는 루틴은 세틀뱅크에서 받은 데이타가 맞는지 확인하는 것이므로 꼭 사용하셔야 합니다
         * 정상적인 결제 건임에도 불구하고 노티 페이지의 오류나 네트웍 문제 등으로 인한 hash 값의 오류가 발생할 수도 있습니다.
         * 그러므로 hash 오류건에 대해서는 오류 발생시 원인을 파악하여 즉시 수정 및 대처해 주셔야 합니다.
         * 그리고 정상적으로 데이터를 처리한 경우에도 세틀뱅크에서 응답을 받지 못한 경우는 결제결과가 중복해서 나갈 수 있으므로 관련한 처리도 고려되어야 합니다
         */
        if ($hashCipher != $input['pktHash']) {
            $input['failMessage'][] = 'hash 인증실패';
            $this->log->info('FAIL:: '.$input['outStatCd'].'::'.$input['mchtTrdNo'], ($input['failMessage'] ?? ''));
            return false;
        }

        if ('0021' == $input['outStatCd']) {
            /** 승인 (결제 / 입금완료 / 결제취소) */
            $lock = cache()->lock('payment_noti_'.$input['mchtTrdNo'], 10);

            try {
                $lock->block(5);
                $resp = DB::transaction(function () use ($model, $class, &$arrNotify) {
                    LogBatch::startBatch();

                    $amount = request()->trdAmt;
                    $amountTxt = number_format($amount).'원';
                    $mchtParam = json_decode(str_replace('\'', '"', request()->mchtParam)); // 상점예약정보

                    if ($model->id != $mchtParam->model_id) {
                        throw new Exception('잘못된 접근입니다.');
                    }

                    // 이미 등록되고 결제 완료된 건이 있는지 확인
                    if (Payment::where([
                        'od_id' => request()->mchtTrdNo,
                        'status' => PaymentStatusEnum::APPROVE,
                    ])->exists()) {
                        throw new Exception('이미 처리완료된 결제건입니다.');
                    }

                    if (request('bizType') == 'C0') {
                        // 결제 취소
                        $class->setPayment($model, 'cancel');
                    } else {
                        // 결제 성공
                        if (request('method') == 'VA') {
                            // 가상계좌 입금
                            $payment = $class->setPayment($model, 'deposit');
                        } else {
                            // 카드 결제
                            if (!$this->productValidation($mchtParam->product_code, $amount)) {
                                throw new Exception('금액이 상이하거나 존재하지 않는 이용권 코드입니다.');
                            }

                            $payment = $class->setPayment($model, 'pay');
                        }

                        if ($model instanceof Academy) {
                            /** 학원 프로세스 */
                        } elseif ($model instanceof Student) {
                            /** 학생(B2C) 프로세스 */
                            $student = $model;

                            // 이용권 결제
                            $product = $this->config('products')[$mchtParam->product_code];

                            // 기간산정
                            if ($student->service_end_date >= now()->format('Y-m-d')) {
                                $serviceStartDate = $student->service_start_date;
                                $serviceEndDate = date('Y-m-d', strtotime('+'.$product['day'].' days', strtotime($student->service_end_date)));

                                $service_date = [
                                    'start' => $student->service_end_date->format('Y-m-d'),
                                    'end' => $serviceEndDate,
                                ];
                            } else {
                                $serviceStartDate = now()->format('Y-m-d');
                                $serviceEndDate = date('Y-m-d', strtotime('+'.$product['day'].' days', strtotime(date('Y-m-d'))));

                                $service_date = [
                                    'start' => $serviceStartDate,
                                    'end' => $serviceEndDate,
                                ];
                            }

                            $serviceStartResult = (new StudentService())->serviceStartOne($student, [
                                'service_start_date' => $serviceStartDate,
                                'service_end_date' => $serviceEndDate,
                            ]);

                            if (!$serviceStartResult['result']) {
                                throw new Exception($serviceStartResult['message']);
                            }

                            $payment->extra += [
                                'service_date' => $service_date,
                            ];
                            $payment->save();

                            // 알림톡 발송 : 이용권 결제 완료
                            $arrNotify['type'] = 'service';
                            $arrNotify['params'] = [$product['name'], $payment->od_id, $amountTxt];
                        }
                    }

                    LogBatch::endBatch();

                    return true;
                });
            } catch (LockTimeoutException $e) {
                throw new Exception('응답 대기시간 초과-'.$e->getMessage());
            } catch (Throwable $e) {
                if (in_array(request('method'), ['CA', 'PZ'])) {
                    $cancelResult = $this->cancel([
                        'mid' => request()->mchtId,
                        'trd_no' => request()->trd_no,
                        'method' => request('method'),
                        'amount' => request()->trdAmt,
                        'od_id' => request()->mchtTrdNo,
                        'cancel_order' => 1,
                    ]);
                    $this->log->info('cancelResult :: ', $cancelResult);
                }

                $input['failMessage'][] = $e->getMessage();
            } finally {
                optional($lock)->release();
            }
        } elseif ('0051' == $input['outStatCd']) {
            /** 가상계좌 채번 발급상태 (입금대기) */
            $resp = true;

            try {
                $amount = request()->trdAmt;
                $mchtParam = json_decode(str_replace('\'', '"', request()->mchtParam)); // 상점예약정보

                // 이미 발급된 건이 있는지 확인
                if (Payment::where([
                    'od_id' => request()->mchtTrdNo,
                ])->exists()) {
                    throw new Exception('이미 처리완료된 결제건입니다.');
                }

                if (!$this->productValidation($mchtParam->product_code, $amount)) {
                    throw new Exception('금액이 상이하거나 존재하지 않는 이용권 코드');
                }

                $class->setPayment($model, 'account');
            } catch (Throwable $e) {
                $input['failMessage'][] = $e->getMessage();
                $input['failMessage'][] = $this->vbankAccountCancel(); // 가상계좌 채번 취소
                $resp = false;
            }
        } else {
            /**
             * 기타 코드
             */
            $input['failMessage'][] = '기타코드';
        }

        if ($resp) {
            if ($arrNotify['type'] == 'service') {
                $model->notify(new ServicePaymentCompletedNotification($arrNotify['params']));
            }

            $this->log->info('SUCCESS :: '.$input['outStatCd'].':: '.$input['mchtTrdNo']);
        } else {
            $this->log->info('FAIL:: '.$input['outStatCd'].'::'.$input['mchtTrdNo'], ($input['failMessage'] ?? ''));
        }

        return $resp;
    }

    public function productValidation($productCode, $amount): bool
    {
        $products = $this->config('products');

        if (!$productCode || !isset($products[$productCode])) {
            return false;
        }

        $product = $products[$productCode];
        $price = $product['amount']['sale'] ?: $product['amount']['origin'];

        if ($price != $amount) {
            return false;
        }

        return true;
    }

    /**
     * 결과 view 처리
     */
    public function payReceiveResult(): array
    {
        $input = request()->all();
        $this->log->info('payReceiveResult : ', $input);

        // null to string
        $input = array_map(function ($v) {
            return $v ?? '';
        }, $input);


        /** 응답 파라미터 세팅 */
        $resParams = [
            'mchtId' => ($input['mchtId'] ?? ''),               //상점아이디
            'outStatCd' => ($input['outStatCd'] ?? ''),         //결과코드
            'outRsltCd' => ($input['outRsltCd'] ?? ''),         //거절코드
            'outRsltMsg' => ($input['outRsltMsg'] ?? ''),       //결과메세지
            'method' => ($input['method'] ?? ''),               //결제수단
            'mchtTrdNo' => ($input['mchtTrdNo'] ?? ''),         //상점주문번호
            'mchtCustId' => ($input['mchtCustId'] ?? ''),       //상점고객아이디
            'trdNo' => ($input['trdNo'] ?? ''),                 //세틀뱅크 거래번호
            'trdAmt' => ($input['trdAmt'] ?? ''),               //거래금액
            'mchtParam' => ($input['mchtParam'] ?? ''),         //상점예약필드
            'authDt' => ($input['authDt'] ?? ''),               //승인일시
            'authNo' => ($input['authNo'] ?? ''),               //승인번호
            'reqIssueDt' => ($input['reqIssueDt'] ?? ''),       //채번요청일시
            'intMon' => ($input['intMon'] ?? ''),               //할부개월수
            'fnNm' => ($input['fnNm'] ?? ''),                   //카드사명
            'fnCd' => ($input['fnCd'] ?? ''),                   //카드사코드
            'pointTrdNo' => ($input['pointTrdNo'] ?? ''),       //포인트거래번호
            'pointTrdAmt' => ($input['pointTrdAmt'] ?? ''),     //포인트거래금액
            'cardTrdAmt' => ($input['cardTrdAmt'] ?? ''),       //신용카드결제금액
            'vtlAcntNo' => ($input['vtlAcntNo'] ?? ''),         //가상계좌번호
            'expireDt' => ($input['expireDt'] ?? ''),           //입금기한
            'cphoneNo' => ($input['cphoneNo'] ?? ''),           //휴대폰번호
            'billKey' => ($input['billKey'] ?? ''),             //자동결제키
            'csrcAmt' => ($input['csrcAmt'] ?? ''),             //현금영수증 발급 금액(네이버페이)
        ];


        // AES256 복호화 필요 파라미터
        $decryptParams = ['mchtCustId', 'trdAmt', 'pointTrdAmt', 'cardTrdAmt', 'vtlAcntNo', 'cphoneNo', 'csrcAmt'];

        /** AES256 복호화 처리(Base64 decoding -> AES-256-ECB decrypt ) */
        try {
            foreach ($decryptParams as $i) {
                if (array_key_exists($i, $resParams)) {
                    $aesCipher = trim($resParams[$i]);
                    if ('' != $aesCipher) {
                        $cipherRaw = base64_decode($aesCipher);
                        if ($cipherRaw === false) {
                            throw new Exception('base64_decode() error'.$i);
                        }
                        $aesPlain = openssl_decrypt($cipherRaw, 'AES-256-ECB', $this->aesKey, OPENSSL_RAW_DATA);
                        if ($aesPlain === false) {
                            throw new Exception('openssl_decrypt() error'.$i);
                        }

                        $resParams[$i] = $aesPlain;//복호화된 데이터로 세팅
                    }
                }
            }
        } catch (Exception $e) {
            $this->log->info('['.$resParams['mchtTrdNo'].'][AES256 Decrypt] ! : '.$e->getMessage());
        }

        return $resParams;
    }

    /**
     * type = 'S' 학생 ,AD-직접결제, AA-자동결제 (spay)
     *
     * 주문번호 생성
     */
    public function genOrderId(): string
    {
        return request()->type.now()->format('YmdHis').sprintf('%04d', rand(1, 9999));
    }

    /**
     * 가상계좌 채번 취소
     * @return string
     * @throws Exception
     */
    private function vbankAccountCancel(): string
    {
        $input = request()->all();

        $param = [];
        $param['params']['mchtId'] = $input['mchtId']; // 상점아이디
        $param['params']['ver'] = self::VER;
        $param['params']['method'] = 'VA';
        $param['params']['bizType'] = 'A2';
        $param['params']['encCd'] = '23';
        $param['params']['mchtTrdNo'] = $input['mchtTrdNo'];
        $param['params']['trdDt'] = now()->format('Ymd');
        $param['params']['trdTm'] = now()->format('His');

        $hashPlain = $param['params']['trdDt'].$param['params']['trdTm'].$param['params']['mchtId'].$param['params']['mchtTrdNo'].'0'.$this->config('pg.license_key');

        $param['data']['pktHash'] = $this->hashCipher($hashPlain, $param['params']['mchtTrdNo']);
        $param['data']['orgTrdNo'] = $input['trdNo'];
        $param['data']['vAcntNo'] = $input['vAcntNo'];

        $result = Http::post($this->config('pg.vbank_account_cancel'), $param)->json();

        if ($result['params']['outStatCd'] == '0021') {
            return '가상계좌 채번 취소 성공';
        } else {
            return '['.$result['params']['outRsltCd'].'] 가상계좌 채번 취소 실패';
        }
    }

    /**
     *
     * PG 사 결제 취소
     * POST | admin/payments/{payment}/cancel | admin.payments.cancel
     * request
     *     mchtId = 상점 id
     *     mchtTrdNo = od_id
     *      trdNo = pg 승인번호
     * refund_acnt_no
     *
     *
     */
    public function cancel($data): array
    {
        try {
            Validator::make($data, [
                'method' => ['required', new Enum(PaymentMethodEnum::class)],
                'od_id' => 'required',
            ])->validate();

            $mid = $this->config('pg.mid');

            if ($data['method'] == 'PZ') {
                // 현금영수증의 테스트 아이디는 mid_test
                $mid = $this->config('pg.pz_mid');
            }

            if ($data['method'] == 'VA') {
                // 가상계좌
                $mid = $this->config('pg.va_mid');
            }

            $param = [];
            $param['params']['mchtId'] = $mid; // 상점아이디

            $param['params']['ver'] = self::VER;              //전문의 버전
            $param['params']['method'] = $data['method'];        //결제수단 CA 카드, VA 가상계좌, PZ 간편결제
            $param['params']['bizType'] = 'C0';      //업무 구분코드
            $param['params']['encCd'] = self::ENCCD;          //암호화 구분 코드
            $param['params']['mchtTrdNo'] = $data['od_id']; //상점에서 생성하는 고유한 거래번호
            $param['params']['trdDt'] = now()->format('Ymd');    //현재 전문을 전송하는 일자(YYYYMMDD)
            $param['params']['trdTm'] = now()->format('His');    //현재 전문을 전송하는 시간(HHMMSS)
            $trdAmt = $data['amount']; //취소 거래금액

            $hashPlain = $param['params']['trdDt'].$param['params']['trdTm'].$param['params']['mchtId'].$param['params']['mchtTrdNo'].$trdAmt.$this->config('pg.license_key');

            //AES 암호화데이터
            $aes = [];
            $aes['cnclAmt'] = $trdAmt;

            if ($data['method'] == PaymentMethodEnum::VA->value) {
                $aes['refundAcntNo'] = $data['refundAcntNo'] ?? ''; // 환불 계좌번호
            }

            $aes = $this->encParams($aes, $data['od_id'], $this->config('pg.aes256_key'));

            $param['data']['pktHash'] = $this->hashCipher($hashPlain, $param['params']['mchtTrdNo']); //SHA256 방식으로 생성한 해쉬값
            $param['data']['orgTrdNo'] = $data['trd_no'];    //결제 시, 세틀뱅크에서 발급한 거래번호
            $param['data']['crcCd'] = 'KRW';                    //통화구분
            $param['data']['cnclOrd'] = $data['cancel_order'];      //001부터 시작. 부분취소 2회차의 경우 002(공백일 경우 자동 세팅)
            $param['data']['cnclAmt'] = $aes['cnclAmt'];        //취소금액

            $cancelUrl = self::CANCEL_URL;

            //가상계좌 취소시 추가 params
            if ($data['method'] == 'VA') {
                $cancelUrl = self::VBANK_CANCEL_URL;
                $param['data']['refundBankCd'] = $data['refundBankCd'] ?? ''; //환불은행코드
                $param['data']['refundAcntNo'] = $aes['refundAcntNo'] ?? ''; // 환불계좌예금주명
                $param['data']['refundDpstrNm'] = $data['refundDpstrNm'] ?? ''; // 환불사유내용
            }

            $result = Http::post($this->config('pg.cancel_server').$cancelUrl, $param)->json();

            $this->log->info('PaymentCancel :: ', $result);

            if (isset($result['params']['outStatCd']) && $result['params']['outStatCd'] == '0021') {
                return [
                    'success' => true,
                ];
            } else {
                throw new Exception($result['params']['outRsltMsg'] ?? '');
            }
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * 현금 영수증 발행
     * @param Payment $payment
     * @param $identityGb
     * @param $identity
     * @return \Illuminate\Http\Client\Response
     */
    public function cashReceipt(Payment $payment, $identityGb, $identity): \Illuminate\Http\Client\Response
    {
        $trDt = $payment->approved_at->format('YmdHis');

        $mid = $this->config('pg.mid');
        if ($mid == 'nxca_jt_il') {
            // 현금영수증의 테스트 아이디는 mid_test
            $mid = 'mid_test';
        }
        $assort = 0; // 0 승인요청, 1 취소요청
        $transNo = $payment->od_id;
        $amt = round($payment->amount * 90 / 100); // 부가세 제외금액
        $vat = $payment->amount - $amt; // 부가세

        return Http::asForm()->post($this->config('pg.cash_receipt_server'), [
            'mid' => $mid,
            'assort' => $assort,
            'transNo' => $transNo,
            'trDt' => $trDt,
            'amt' => $amt,                  // 공급가액
            'vat' => $vat,                  // 부가세
            'purpose' => 0,                 // 소득공제
            'identityGb' => $identityGb,    // 등록번호 구분
            'identity' => $identity,        // 등록번호
        ]);
    }

    /**
     *  조합 필드:$hashPlain, 주문번호: $mchtTrdNo
     *  SHA256 해쉬 처리
     *
     * @param $hashPlain
     * @param $mchtTrdNo
     * @return string
     * @throws Exception
     */
    public function hashCipher($hashPlain, $mchtTrdNo)
    {
        $hashCipher = '';

        /** SHA256 해쉬 처리 */
        try {
            $hashCipher = hash('sha256', $hashPlain); //해쉬 값
            $this->log->info('hashCipher :: '.$hashPlain);
        } catch (Exception $ex) {
            $this->log->info(
                '['.$mchtTrdNo.'][SHA256 HASHING] Hashing Fail! : '.$ex->getMessage()
            );
            throw new Exception('Error occurred during hashing!');
        } finally {
            return $hashCipher; // sha256 해쉬 결과
        }
    }

    /**
     *  AES256 암호화 처리(AES-256-ECB encrypt -> Base64 encoding)
     */
    public function encParams($params, $mchtTrdNo, $aesKey)
    {
        try {
            foreach ($params as $key => $value) {
                $aesPlain = $params[$key];
                if (!('' == $aesPlain)) {
                    $chiperRaw = openssl_encrypt($aesPlain, 'AES-256-ECB', $aesKey, OPENSSL_RAW_DATA);
                    $aesCipher = base64_encode($chiperRaw);

                    $params[$key] = $aesCipher; //암호화된 데이터로 세팅
                }
            }
        } catch (Exception $ex) {
            $this->log->info('['.$mchtTrdNo.'][AES256 Encrypt] AES256 Fail! : '.$ex->getMessage());
            throw new Exception('aes256 encrypt fail');
        } finally {
            return $params; //aes256 암호화 결과
        }
    }
}
