<?php

namespace App\Http\Requests;

use App\Models\Student;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property Student $student
 */
class StudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        /*
        if (auth()->user() instanceof Teacher) {
            return auth()->user()->academy_id == $this->academy->id;
        } else {
            return true;
        }
        */
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
                'academy_id' => 'integer|exists:academies,id|nullable',
                'name' => ['required', 'string'],
                'birth_date' => 'date',
                'grade' => [
                    Rule::in(array_keys(config('dailykor.test.questions.level')))
                ],
                'term' => 'integer|in:1,2',
                'manager_memo' => 'string',
                'parents_name' => 'string',
                'remove_avatar' => 'bool',
                'service_start_date' => 'date|nullable',
                'service_end_date' => 'date|nullable',
                'phone' => ['numeric', 'regex:/^(010|011|016|017|018|019)[0-9]{7,8}$/'],
                'parents_phone' => ['required', 'numeric', 'regex:/^(010|011|016|017|018|019)[0-9]{7,8}$/'],
                'password' => [
                    'nullable',
                    'min:8',
                    'max:18',
                    'regex:/[A-Za-z]/',  // 최소 한 개의 영문자
                    'regex:/[0-9]/',      // 최소 한 개의 숫자
                    'regex:/[^A-Za-z0-9]/' // 최소 한 개의 특수문자
                ],
            ]
            +
            ($this->isMethod('POST') ? $this->store() : $this->update());
    }

    protected function store(): array
    {
        return [
            'access_id' => ['required', Rule::unique('students')->whereNull('deleted_at'), 'regex:/^[a-z0-9]{6,12}$/'],
            'password' => [
                'required',
                'min:8',
                'max:18',
                'regex:/[A-Za-z]/',  // 최소 한 개의 영문자
                'regex:/[0-9]/',      // 최소 한 개의 숫자
                'regex:/[^A-Za-z0-9]/' // 최소 한 개의 특수문자
            ],
        ];
    }

    protected function update(): array
    {
        return [
            'access_id' => ['required', 'unique:students,access_id,'.$this->student->id],
        ];
    }
}
