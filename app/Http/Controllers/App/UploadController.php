<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\BaseController;

class UploadController extends BaseController
{
    /*
     * POST | api/upload/image | app.upload.image
     */
    public function imageStore(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4056',
            'path' => 'string'
        ]);
        $path = Storage::disk('s3')->put($request->path ?? 'temp/images', $request->file);
        $url = Storage::disk('s3')->url($path);

        return $this->sendResponse(['path' => $path, 'url' => $url], '임시이미지 업로드성공');
    }
}
