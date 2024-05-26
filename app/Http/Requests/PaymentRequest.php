<?php

namespace App\Http\Requests;

use App\Enums\PaymentCashReceiptEnum;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

/**
 * 결제 validation
 */
class PaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * 현금영수증 등록
     * @return array
     */
    public function issueCashReceiptValidate(): array
    {
        return [
            'od_id' => ['required'],
            'identity' => ['required', 'integer'],
            'identity_gb' => ['required', new Enum(PaymentCashReceiptEnum::class)],
        ];
    }

    /**
     * 현금영수증 등록
     * @return array
     */
    public function resultValidate(): array
    {
        return [
            'mchtTrdNo' => ['required'],
            'mchtParam' => ['required'],
            'method' => ['required'],
            'outRsltCd' => ['required'],
            'outRsltMsg' => ['required'],
            'outStatCd' => ['required'],
            'trdAmt' => ['required'],
            'trdNo' => ['required'],
//            'fnCd' => ['required'],
//            'fnNm' => ['required'],

            // 가상계좌 발급시
            'vtlAcntNo' => ['required_if:outStatCd,0051'],
            'expireDt' => ['required_if:outStatCd,0051'],
        ];
    }

    /**
     * 결제 취소
     * @return array
     */
    public function cancelValidate(): array
    {
        return [
            'amount' => ['required', 'integer'],
            'memo' => ['string'],
        ];
    }
}
