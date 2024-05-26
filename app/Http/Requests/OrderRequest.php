<?php

namespace App\Http\Requests;

use App\Enums\PaymentCashReceiptEnum;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

/**
 * 결제 validation
 */
class OrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * 결제 취소
     * @return array
     */
    public function cancelValidate(): array
    {
        return [
            'od_id' => ['required'],
        ];
    }

}
