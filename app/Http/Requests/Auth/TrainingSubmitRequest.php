<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class TrainingSubmitRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'step_result_id' => ['strict_integer', 'nullable'],
            'question_id' => ['required', 'strict_integer'],
            'answers' => ['required'],
            'answer_row_index' => ['strict_integer', 'nullable'],
            'answer_col_index' => ['strict_integer', 'nullable'],
            'timer' => ['required', 'strict_integer'],
        ];
    }
}
