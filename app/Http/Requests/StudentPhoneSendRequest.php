<?php

namespace App\Http\Requests;

use App\Models\Student;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property Student $student
 */
class StudentPhoneSendRequest extends FormRequest
{
    public mixed $phone;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'phone' => 'required|numeric|digits:11|regex:/^(010)[0-9]{4}[0-9]{4}$/'
        ];
    }
}
