<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Http\Requests\AcademyRequest;
use App\Models\Academy;
use App\Services\AcademyService;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Role;

class AcademyController extends BaseController
{
    public function __construct(
        public string $name = '학원',
    ) {
    }


    /**
     * 등록
     * POST | api/academies | api.academies.store
     */
    public function store(AcademyRequest $request): JsonResponse
    {
        try {
            $academy = new Academy();

            $academy->fill([
                'name' => $request->name
            ])->setActivitylogOptions([
                'description' => '외부 학원 등록',
                'is_show' => 1,
            ])->save();

            // 학원에 대한 토큰 발급 (만료기간 없음)
            $token = $academy->createToken('academy_access_token')->plainTextToken;
            $academy->assignRole(Role::where('name', 'owner')->first());

            $response = ['id' => $academy->id, 'name' => $academy->name, 'token' => $token];

            return $this->sendResponse($response, '학원 등록 성공');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), [], $e->getCode());
        }
    }


    /**
     * 상세
     * GET|HEAD | api/academies/{academy} | api.academies.show
     */
    public function show(Academy $academy): JsonResponse
    {
        try {
            $user = request()->user();
            $isAcademyUser = $user instanceof Academy;
            // 학원 유저는 다른 학원 조회 불가능
            if ($isAcademyUser && $user->id !== $academy->id) {
                return response()->json(['error' => __('messages.error_403')], 403);
            };

            return $this->sendResponse(['academy' => $academy], '성공');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), [], $e->getCode());
        }
    }

    /**
     * 수정
     * PUT|PATCH | api/academies/{academy} | api.academies.update
     */
    public function update(AcademyRequest $request, Academy $academy): JsonResponse
    {
        try {
            $user = request()->user();
            $isAcademyUser = $user instanceof Academy;
            // 학원 유저는 다른 학원 수정 불가능
            if ($isAcademyUser && $user->id !== $academy->id) {
                return response()->json(['error' => __('messages.error_403')], 403);
            };

            $input = $request->all();
            $input['manager_memo'] = $input['manager_memo'] ?? '';

            $academy->fill($input)->setActivitylogOptions([
                'description' => $this->name.'가 수정 되었습니다.',
                'is_show' => 1,
            ])->save();

            // tags
            if (isset($input['tags'])) {
                if (!$input['tags']) {
                    $academy->tags()->detach();
                } else {
                    $academy->syncTagsWithType($input['tags'], 'admin.academies');
                }
            }

            $academyService = new AcademyService($academy);

            if (request()->remove_logo) {
                $academyService->logoRemove();
            }
            $academyService->logoUpload();

            return $this->sendResponse(['academy' => $academy], '성공');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), [], $e->getCode());
        }
    }

    /**
     * 삭제 (soft)
     * DELETE | api/academies/{academy} | api.academies.destroy
     */
    public function destroy(Academy $academy): JsonResponse
    {
        try {
            $message = $academy->name.'학원삭제';
            $academy->setActivityLogOptions([
                'description' => $message,
                'is_show' => 1,
            ])->delete();

            return $this->sendResponse([], '학원 삭제 성공');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), [], $e->getCode());
        }
    }
}
