<?php

namespace App\Http\Requests\App;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StudentGrandAndTermRequest extends FormRequest
{
    public mixed $grade;
    public mixed $term;

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
            'grade' => ['required', Rule::in(array_keys(dbcode('students.grade')))],
            'term' => 'required|in:1,2',
        ];
    }

}
