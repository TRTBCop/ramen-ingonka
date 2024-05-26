<?php

namespace App\Http\Requests\App;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property int $timer
 */
class TestRequest extends FormRequest
{
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
            'question_id' => 'required|integer',
            'answers' => 'required|array', // 정답
            'meta_cognition' => 'integer|in:0,1,2', // 메타인지
            'timer' => 'integer' // 학습시간
        ];
    }
}
