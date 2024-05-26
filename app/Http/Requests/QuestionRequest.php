<?php

namespace App\Http\Requests;

use App\Enums\QuestionLevelEnum;
use App\Models\Academy;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

/**
 * @property Academy $academy
 * @property mixed $arr_service_status
 * @property mixed $password
 * @property mixed $access_id
 * @property mixed $name
 * @property mixed $owner_name
 */
class QuestionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function attributes(): array
    {
        return [
            'question' => '문제풀이과정',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
                'curriculum_id' => 'strict_integer|exists:curricula,id|nullable', // 소단원
                'level' => ['strict_integer', new Enum(QuestionLevelEnum::class)], // 난이도
                'question' => '', // 문제풀이과정문제
                'inquiry' => '', // 발문
                'options' => '', // 보기

                'answers' => 'present|array', // 답안
                'answers.*.type' => 'required|strict_integer|in:1,2,3', // 답안타입
                'answers.*.action' => 'strict_integer|in:1,2,3,4', // 행동영역
                'answers.*.answer.*' => 'required', // 정답 값

                'answers.*.choice_symbol' => 'required_if:answers.*.type,1|bool', // 기호
                'answers.*.choices' => 'required_if:answers.*.type,2|array', // 선지 선택형인경우 필수
                'explanation' => '', // 해설

                'tags' => 'array',
                'tags.*' => 'array',
            ]
            +
            ($this->isMethod('POST') ? $this->store() : $this->update());
    }

    protected function store(): array
    {
        return [];
    }

    protected function update(): array
    {
        return [];
    }
}
