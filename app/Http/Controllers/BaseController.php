<?php

namespace App\Http\Controllers;

use App\Http\Resources\ListCollection;
use App\Traits\HttpStatusTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BaseController extends Controller
{
    use HttpStatusTrait;

    public string $name;
    protected array $statusErrorCodes = [
        self::HTTP_BAD_REQUEST,
        self::HTTP_UNAUTHORIZED,
        self::HTTP_FORBIDDEN,
        self::HTTP_NOT_FOUND,
        self::HTTP_UNPROCESSABLE_ENTITY,
        self::HTTP_INTERNAL_SERVER_ERROR,
    ];

    /**
     * 목록 불러오기
     * @param $model
     * @param null $option
     * @param null $append
     * @return AnonymousResourceCollection
     */
    public function getCollection($model, $option = null, $append = null): AnonymousResourceCollection
    {
        if (gettype($model) == 'object') {
            $query = $model->listFilter($option);
        } else {
            $query = $model::listFilter($option);
        }
        if (request()->show_all) {
            $collection = $query->get();
        } else {
            $pageSize = (isset(request()->pageSize) && is_numeric(request()->pageSize)) ? request()->pageSize : 50;
            $collection = $query->paginate($pageSize);
        }

        if ($append) {
            $collection->append($append);
        }
        return ListCollection::collection($collection);
    }

    /*
     * update method 가 있어야함
     */
    public function setStore($child, $model, $request)
    {
        return $child->update($request, $model);
    }

    /**
     * success response method.
     *
     * @param $result
     * @param $message
     * @return JsonResponse
     */
    public function sendResponse($result, $message): JsonResponse
    {
        $response = [
            'success' => true,
            'data' => $result,
            'message' => $message,
        ];

        $headers = [
            'app-latest-release' => getenv('APP_LATEST_RELEASE') ?? '',
        ];

        return response()->json($response, self::HTTP_OK, $headers);
    }

    /**
     * return error response.
     *
     * @param $error
     * @param array|string $errorMessages
     * @param int $code
     * @return JsonResponse
     */
    public function sendError($error, array|string $errorMessages = [], mixed $code = 404): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, (isset($code) && in_array($code, $this->statusErrorCodes)) ? $code : self::HTTP_INTERNAL_SERVER_ERROR);
    }
}
