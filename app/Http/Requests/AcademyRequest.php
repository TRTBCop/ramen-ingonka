<?php

namespace App\Http\Requests;

use App\Models\Academy;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property Academy $academy
 * @property mixed $arr_service_status
 * @property mixed $password
 * @property mixed $access_id
 * @property mixed $name
 * @property mixed $owner_name
 */
class AcademyRequest extends FormRequest
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
                'status' => ['integer'],
            ]
            +
            ($this->isMethod('POST') ? $this->store() : $this->update());
    }

    protected function store(): array
    {
        return [
            //'access_id' => ['required', 'alpha_dash', 'unique:teachers'],
            //'password' => ['required'],
        ];
    }

    protected function update(): array
    {
        return [];
    }

}
