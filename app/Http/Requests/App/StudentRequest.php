<?php

namespace App\Http\Requests\App;

use App\Models\Student;
use App\Rules\AlphaNumericHangulRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property Student $student
 * @property int $student_phone_id
 * @property string $code
 * @property mixed $phone
 * @property mixed $parents_phone
 * @property mixed $password
 * @property mixed $student_id
 */
class StudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


    /**
     * 회원가입 유효성 검사
     * @return array
     */
    public function store(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:10', new AlphaNumericHangulRule()],
            'phone' => ['numeric', 'regex:/^(010|011|016|017|018|019)[0-9]{7,8}$/'],
            'birth_date' => ['required', 'date_format:Y-m-d', 'before:today'],
            'parents_name' => ['required', 'string', 'max:10', new AlphaNumericHangulRule()],
            'parents_phone' => ['required', 'numeric', 'regex:/^(010|011|016|017|018|019)[0-9]{7,8}$/'],
            'marketing_consent' => ['required', 'bool'],
            'naver_id' => [],
            'kakao_id' => [],
        ];

        // access_id rule
        $accessIdRules = [
            'required',
            Rule::unique('students')->whereNull('deleted_at'),
            'regex:/^[a-z0-9]{6,12}$/'
        ];

        // password, c_password rule
        $passwordRules = [
            'required',
            'min:8',
            'max:18',
            'regex:/[A-Za-z]/',  // 최소 한 개의 영문자
            'regex:/[0-9]/',      // 최소 한 개의 숫자
            'regex:/[^A-Za-z0-9]/', // 최소 한 개의 특수문자
            'same:c_password',
        ];

        // naver_id나 kakao_id가 존재하지 않으면 유효성 검사를 수행합니다.
        if (!request()->has('naver_id') && !request()->has('kakao_id')) {
            $rules['access_id'] = $accessIdRules;
            $rules['password'] = $passwordRules;
            $rules['c_password'] = ['required', 'same:password'];
        }

        return $rules;
    }

    /**
     * 회원정보 수정 휴효성 검사
     * @return array|array[]
     */
    public function update(): array
    {
        return [
            'password' => [
                'min:8',
                'max:18',
                'regex:/[A-Za-z]/',  // 최소 한 개의 영문자
                'regex:/[0-9]/',      // 최소 한 개의 숫자
                'regex:/[^A-Za-z0-9]/' // 최소 한 개의 특수문자
            ],
            'c_password' => ['same:password'],

            // 정보수정
            'birth_date' => ['required', 'date_format:Y-m-d', 'before:today'],
            'parents_name' => ['required', 'string', 'max:10', new AlphaNumericHangulRule()],
            'parents_phone' => ['required', 'numeric', 'regex:/^(010|011|016|017|018|019)[0-9]{7,8}$/'],

            'phone' => ['numeric', 'regex:/^(010|011|016|017|018|019)[0-9]{7,8}$/'],
            'student_phone_id' => ['integer'],
            'marketing_consent' => ['required', 'bool'],
        ];
    }

    /**
     * 휴대폰 인증번호 발송 유효성 검사
     * @return array
     */
    public function verificationAccountCheck(): array
    {
        return [
            'access_id' => ['required', 'min:6', 'max:12', Rule::unique('students')->whereNull('deleted_at'), 'regex:/^[a-z0-9]{6,12}$/'],
        ];
    }

    /**
     * 휴대폰 인증번호 발송 유효성 검사
     * @return array
     */
    public function verificationSendValidate(): array
    {
        return [
            'phone' => ['required', 'numeric', 'regex:/^(010|011|016|017|018|019)[0-9]{7,8}$/']
        ];
    }

    /**
     * 휴대폰 인증번호 유효성 검사
     * @return array
     */
    public function verificationCodeValidate(): array
    {
        return [
            'phone' => ['required', 'numeric', 'regex:/^(010|011|016|017|018|019)[0-9]{7,8}$/'],
            'code' => ['required', 'numeric', 'digits:4'],
        ];
    }

    /**
     * 아이디 찾기 유효성 검사
     * @return array
     */
    public function findAccessIdValidate(): array
    {
        // 학생 이름 && 학부모 휴대폰
        return [
            'name' => ['required', 'string', 'max:10', new AlphaNumericHangulRule()],
            'parents_phone' => ['required', 'numeric', 'regex:/^(010|011|016|017|018|019)[0-9]{7,8}$/']
        ];
    }

    /**
     * 아이디 찾기(전체) 유효성 검사
     * @return array
     */
    public function findAccessIdFullValidate(): array
    {
        return [
            'student_id' => ['required', 'numeric'],
            'student_phone_id' => ['required', 'numeric'],
            'parents_phone' => ['required', 'numeric', 'regex:/^(010|011|016|017|018|019)[0-9]{7,8}$/']
        ];
    }

    /**
     * 비밀번호 찾기 유효성 검사
     * @return array
     */
    public function findPasswordValidate(): array
    {
        return [
            'access_id' => ['required', 'regex:/^[a-z0-9]{6,12}$/'],    // 비밀번호 찾기에서는 id 중복체크 안함
            'name' => ['required', 'string', 'max:10', new AlphaNumericHangulRule()],
            'parents_phone' => ['required', 'numeric', 'regex:/^(010|011|016|017|018|019)[0-9]{7,8}$/']
        ];
    }

    /**
     * 비밀번호 변경 유효성 검사
     * @return array
     */
    public function resetPasswordValidate(): array
    {
        return [
            'student_id' => ['required', 'integer'],
            'password' => [
                'required',
                'min:8',
                'max:18',
                'regex:/[A-Za-z]/',  // 최소 한 개의 영문자
                'regex:/[0-9]/',      // 최소 한 개의 숫자
                'regex:/[^A-Za-z0-9]/' // 최소 한 개의 특수문자
            ],
            'c_password' => ['required', 'same:password'],
            'student_phone_id' => ['required', 'integer'],
        ];
    }

    public function messages(): array
    {
        return [
            'student_phone_id.required' => '인증번호를 요청해주세요.',
            'code.required' => '휴대폰 인증코드는 필수입니다.',
        ];
    }
}
