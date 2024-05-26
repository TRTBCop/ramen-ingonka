<?php

namespace Database\Factories;

use App\Enums\PaymentMethodEnum;
use App\Enums\PaymentStatusEnum;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $odId = now()->format('YmdHis') . fake()->numerify('####');
        $user = Student::factory()->create();
        $pgResult = [];
        $status = PaymentStatusEnum::APPROVE;

        $rand = rand(0,2);
        if ($rand == 0) {
            $method = PaymentMethodEnum::CA;
        } else if ($rand == 1) {
            $method = PaymentMethodEnum::VA;
        } else {
            $method = PaymentMethodEnum::PZ;
        }

        if ($method === PaymentMethodEnum::CA) {
            $trdNo = 'STFP_PGCAnxca_jt_il' . now()->format('ymdHis') . Str::random(1) . fake()->numerify('#######');
            $amount = '160000';
            $approvedAt = now()->subDays(rand(10, 20));
            $pgResult[] = [
                "trdNo" => $trdNo,
                "cardCd" => "HDC",
                "cardNm" => "현대비자개인일반",
                "cardNo" => "402857******9807",
                "mchtId" => "nxca_jt_il",
                "method" => "CA",
                "trdAmt" => $amount,
                "trdDtm" => "20231211133028",
                "bizType" => "B0",
                "pktHash" => "c4f98a328a60d8817c11a5fbd9ab3b8eacce1f2571fbf677b584a211792fd542",
                "mchtName" => "리딩수학",
                "pmtprdNm" => "3개월",
                "instmtMon" => "00",
                "mchtParam" => "{\"model_id\":\"".$user->id."\",\"model\":\"App\\\\Models\\\\Student\",\"product_code\":\"A03\"}",
                "mchtTrdNo" => $odId,
                "outStatCd" => "0021",
                "cardApprNo" => "99254526",
                "mchtCustId" => $user->id,
                "mchtCustNm" => $user->name
            ];
            if (rand(0, 1)) {
                // 환불 여부
                $pgResult[] = [
                    "trdNo" => $trdNo,
                    "cardCd" => "HDC",
                    "cardNm" => "현대비자개인일반",
                    "cardNo" => "402857******9807",
                    "mchtId" => "nxca_jt_il",
                    "method" => "CA",
                    "trdAmt" => $amount,
                    "trdDtm" => "20231211151605",
                    "bizType" => "C0",
                    "pktHash" => "c9af842e5d055b531f48696afe0bddc88ce23ffec8f179ac4328846b6feda7ab",
                    "cnclType" => "00",
                    "mchtName" => "리딩수학",
                    "orgTrdDt" => "20231211",
                    "orgTrdNo" => "STFP_PGCAnxca_jt_il0231211132946M1140394",
                    "pmtprdNm" => "3개월",
                    "instmtMon" => "00",
                    "mchtParam" => "{\"model_id\":".$user->id.",\"model\":\"App\\\\Models\\\\Student\",\"product_code\":\"A03\"}",
                    "mchtTrdNo" => $odId,
                    "outStatCd" => "0021",
                    "cardApprNo" => "99254526",
                    "mchtCustId" => $user->id,
                    "mchtCustNm" => $user->name
                ];
                $cancelAmount = $amount;
                $cancelMemo = fake()->sentence();
                $status = PaymentStatusEnum::CANCEL;
                $canceledAt = now()->subDays(rand(2, 9));
            }
        } else if ($method === PaymentMethodEnum::VA) {
            $trdNo = 'STFP_PGVAnx_mid_il' . now()->format('ymdHis') . Str::random(1) . fake()->numerify('#######');
            $amount = '160000';
            $status = PaymentStatusEnum::WAITING;
            $pgResult[] = [
                "trdNo" => $trdNo,
                "bankCd" => "020",
                "bankNm" => "우리은행",
                "mchtId" => "nx_mid_il",
                "method" => "VA",
                "trdAmt" => $amount,
                "trdDtm" => "20231213132703",
                "bizType" => "A0",
                "dpstrNm" => "도명식",
                "pktHash" => "42ce17921d0f4651a29a9c646f87b40ab7cf15781d5bd32251fcb42fb591edee",
                "vAcntNo" => "47963178918489",
                "expireDt" => "20231223235959",
                "mchtName" => "리딩수학",
                "pmtprdNm" => "3개월",
                "mchtParam" => "{\"model_id\":".$user->id.",\"model\":\"App\\\\Models\\\\Student\",\"product_code\":\"A03\"}",
                "mchtTrdNo" => $odId,
                "outStatCd" => "0051",
                "mchtCustId" => $user->id,
                "mchtCustNm" => $user->name,
                "AcntPrintNm" => $user->name
            ];
            if (rand(0, 1)) {
                // 입금 완료 여부
                $pgResult[] = [
                    "trdNo" => $trdNo,
                    "bankCd" => "020",
                    "bankNm" => "우리은행",
                    "mchtId" => "nx_mid_il",
                    "method" => "VA",
                    "trdAmt" => $amount,
                    "trdDtm" => "20231213132715",
                    "bizType" => "B1",
                    "dpstrNm" => $user->name,
                    "pktHash" => "f9bd15207fb698e7bf852a259390c64b65d2c33187f89e1bf2dc6e8a269d2f20",
                    "vAcntNo" => "47963178918489",
                    "acntType" => "1",
                    "expireDt" => "20231223235959",
                    "mchtName" => "리딩수학",
                    "pmtprdNm" => "3개월",
                    "csrcIssNo" => "TS0921669",
                    "mchtParam" => "{\"model_id\":".$user->id.",\"model\":\"App\\\\Models\\\\Student\",\"product_code\":\"A03\"}",
                    "mchtTrdNo" => "202312131326518601",
                    "outStatCd" => "0021",
                    "mchtCustId" => $user->id,
                    "mchtCustNm" => $user->name,
                    "AcntPrintNm" => $user->name
                ];
                $approvedAt = now()->subDays(rand(10, 20));
                $status = PaymentStatusEnum::APPROVE;
            }
            if (rand(0, 1)) {
                // 현금 영수증 여부
                $cashReceipt = [
                    "no" => "TS".fake()->numerify('#######')
                ];
            }
        } else {
            $trdNo = 'STFP_PGPZhecto_test' . now()->format('ymdHis') . Str::random(1) . fake()->numerify('#######');
            $amount = '1000';
            $approvedAt = now()->subDays(rand(10, 20));
            $pgResult[] = [
                "trdNo" => $trdNo,
                "kkmAmt" => "1000",
                "mchtId" => "hecto_test",
                "method" => "PZ",
                "pntAmt" => "0",
                "trdAmt" => $amount,
                "trdDtm" => "20231213133949",
                "bizType" => "B0",
                "cardAmt" => "0",
                "coupAmt" => "0",
                "pktHash" => "4324b444ea5c9123badcbb0bc40ef2385d11203694ab7c7aadaff37b756232e9",
                "ezpDivCd" => "KKP",
                "pmtprdNm" => "1개월",
                "mchtParam" => "{\"model_id\":".$user->id.",\"model\":\"App\\\\Models\\\\Student\",\"product_code\":\"A01\"}",
                "mchtTrdNo" => $odId,
                "outStatCd" => "0021",
                "csrcIssAmt" => "0",
                "mchtCustId" => $user->id
            ];

            if (rand(0, 1)) {
                // 환불 여부
                $pgResult[] = [
                    "trdNo" => $trdNo,
                    "kkmAmt" => "1000",
                    "mchtId" => "hecto_test",
                    "method" => "PZ",
                    "pntAmt" => "0",
                    "trdAmt" => $amount,
                    "trdDtm" => "20231213134255",
                    "bizType" => "C0",
                    "cardAmt" => "0",
                    "coupAmt" => "0",
                    "pktHash" => "44a8920b87e497c00c32f31f89289712ebbd0a083e27d3b082e2c31a47611066",
                    "cnclType" => "00",
                    "ezpDivCd" => "KKP",
                    "orgTrdDt" => "20231213",
                    "orgTrdNo" => "STFP_PGPZhecto_test0231213133925M1721029",
                    "pmtprdNm" => "1개월",
                    "instmtMon" => "00",
                    "mchtParam" => "{\"model_id\":".$user->id.",\"model\":\"App\\\\Models\\\\Student\",\"product_code\":\"A01\"}",
                    "mchtTrdNo" => $odId,
                    "outStatCd" => "0021",
                    "csrcIssAmt" => "0",
                    "mchtCustId" => $user->id
                ];
                $cancelAmount = $amount;
                $status = PaymentStatusEnum::CANCEL;
                $canceledAt = now()->subDays(rand(2, 9));
            }
        }

        $extra = [];
        $extra['pg_result'] = $pgResult;
        if (isset($cashReceipt)) {
            $extra['cash_receipt'] = $cashReceipt;
        }
        if (isset($cancelMemo)) {
            $extra['cancel_memo'] = $cancelMemo;
        }

        $data = [
            'od_id' => $odId,
            'trd_no' => $trdNo,
            'amount' => $amount,
            'method' => $method,
            'model_type' => Student::class,
            'model_id' => $user->id,
            'status' => $status,
            'extra' => $extra,
        ];
        if (isset($approvedAt)) {
            $data['approved_at'] = $approvedAt;
        }
        if (isset($canceledAt)) {
            $data['canceled_at'] = $canceledAt;
        }
        if (isset($cancelAmount)) {
            $data['cancel_amount'] = $cancelAmount;
        }

        return $data;
    }
}
