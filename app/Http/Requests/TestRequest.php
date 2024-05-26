<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property mixed $tree
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

    public function attributes(): array
    {
        return [
            'contents.questions.*.id' => '문제번호',
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
        return [
            'contents' => 'array',
            'contents.questions' => 'array',
            'contents.questions.*.id' => 'required|strict_integer',
            'contents.questions.*.is_extend' => 'strict_integer',
            'is_published' => 'bool',
        ];
    }

}
