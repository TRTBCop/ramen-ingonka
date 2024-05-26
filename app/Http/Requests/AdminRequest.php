<?php

namespace App\Http\Requests;

use App\Enums\RoleEnum;
use App\Models\Admin;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Enum;

/**
 * @property Admin $admin
 */
class AdminRequest extends FormRequest
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
                'name' => ['required', 'string'],
                'roles.*' => [new Enum(RoleEnum::class)],
            ]
            +
            ($this->isMethod('POST') ? $this->store() : $this->update());
    }

    protected function store(): array
    {
        return [
            'access_id' => ['required', 'unique:admins'],
            'password' => ['required'],
        ];
    }

    protected function update(): array
    {
        return [
            'access_id' => ['required', 'unique:admins,access_id,'.$this->admin?->id],
        ];
    }
}
