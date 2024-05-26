<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property mixed $del_files
 */
class BoardNoticeRequest extends FormRequest
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
                'title' => ['required', 'string'],
                'contents' => ['required', 'string'],
                'scope' => ['array', Rule::in(array_keys(dbcode('board_notices.scope')))],
                'files' => ['array'],
                'del_files' => ['array'],
                'is_published' => ['bool']
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
