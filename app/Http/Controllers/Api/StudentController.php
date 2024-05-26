<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Http\Requests\StudentRequest;
use App\Models\Academy;
use App\Models\Student;
use App\Services\StudentService;
use Illuminate\Http\JsonResponse;

class StudentController extends BaseController
{
    /**
     * 등록
     * POST | api/students | api.students.store
     */
    public function store(StudentRequest $request): JsonResponse
    {
        try {
            $user = auth()->user();
            $student = new Student();
            $student->fill($request->all());


            // 학원일 경우 academy_id 삽입
            if ($user instanceof Academy) {
                $student->academy_id = $user->id;
            }


            // 학원 학생 무제한 사용 처리
            $serviceStartDate = now()->format('Y-m-d');
            $serviceEndDate = now()->addYears(1000)->format('Y-m-d');
            (new StudentService())->serviceStartOne($student, [
                'service_start_date' => $serviceStartDate,
                'service_end_date' => $serviceEndDate,
            ]);

            $student->setActivitylogOptions([
                'description' => '학생 등록',
                'is_show' => 1,
            ])->save();

            $token = $student->createToken('studnet_access_token')->plainTextToken;

            return $this->sendResponse(['student' => $student, 'token' => $token], '학생 등록 성공');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), [], $e->getCode());
        }
    }


    /**
     * 상세
     * GET|HEAD | api/students/{student} | api.students.show
     */
    public function show(Student $student): JsonResponse
    {
        try {
            $user = request()->user();
            $isAcademyUser = $user instanceof Academy;
            // 학원 유저는 다른 학원 유저 조회 불가능
            if ($isAcademyUser && $user->id !== $student->academy->id) {
                return response()->json(['error' => __('messages.error_403')], 403);
            };

            return $this->sendResponse(['student' => $student], '성공');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), [], $e->getCode());
        }
    }


    /**
     * 수정
     * PUT|PATCH | api/students/{student} | api.students.update
     */
    public function update(StudentRequest $request, Student $student): JsonResponse
    {
        try {
            $user = request()->user();
            $isAcademyUser = $user instanceof Academy;
            // 학원 유저는 다른 학원 유저 수정 불가능
            if ($isAcademyUser && $user->id !== $student->academy->id) {
                return response()->json(['error' => __('messages.error_403')], 403);
            };

            $input = $request->validated();

            if (isset($input['password']) && $input['password']) {
                $input['password'] = bcrypt($input['password']);
            } else {
                unset($input['password']);
            }

            $message = $student->name.'학생 정보 수정';
            $student->setActivityLogOptions([
                'description' => $message,
            ])->update($input);

            // 프로필이미지 등록/삭제
            if (request()->remove_avatar) {
                $student->avatarRemove();
            }
            $student->avatarUpload();

            return $this->sendResponse(['student' => $student], '성공');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), [], $e->getCode());
        }
    }

    /**
     * 삭제 (soft)
     * DELETE | api/students/{student} | api.students.destroy
     */
    public function destroy(Student $student): JsonResponse
    {
        try {
            $user = request()->user();
            $isAcademyUser = $user instanceof Academy;
            // 학원 유저는 다른 학원 유저 수정 불가능
            if ($isAcademyUser && $user->id !== $student->academy->id) {
                return response()->json(['error' => __('messages.error_403')], 403);
            };
            $message = $student->name.'학생 삭제';
            $student->setActivityLogOptions([
                'academy_id' => $student->academy_id,
                'is_show' => 1,
                'description' => $message,
            ])->delete();

            return $this->sendResponse([], '성공');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), [], $e->getCode());
        }
    }

    /**
     * 토큰 생성
     * GET|HEAD | api/students/generate-login-url | api.students.generate-login-url
     */
    public function generateLoginUrl(): JsonResponse
    {
        try {
            $user = request()->user();
            $isStudentUser = $user instanceof Student;

            // 학생만 가능
            if (!$isStudentUser) {
                return response()->json(['error' => __('messages.error_403')], 403);
            }



            // 토큰 생성 (만료 3분)
            $token = $user->createToken('student_access_token', ['expires_in' => 3 * 60])->plainTextToken;

            return $this->sendResponse(['token' => $token, 'login_url' => route('app.auth.token-login', $token)], '성공');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), [], $e->getCode());
        }
    }
}
