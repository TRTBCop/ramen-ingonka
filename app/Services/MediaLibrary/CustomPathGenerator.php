<?php

namespace App\Services\MediaLibrary;

use App\Models\Academy;
use App\Models\Admin;
use App\Models\Student;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator as BasePathGenerator;


/**
 * 미디어 경로 랜덤 생성기
 */
class CustomPathGenerator implements BasePathGenerator
{
    /**
     * Get the path for the given media, relative to the root storage path.
     */
    public function getPath(Media $media): string
    {
        $modelPath = '';
        switch ($media->model_type) {
            case Admin::class :
                $modelPath = 'admins'.DIRECTORY_SEPARATOR;
                break;
            case Academy::class :
                $modelPath = 'academies'.DIRECTORY_SEPARATOR;
                break;
            case Student::class :
                $modelPath = 'students'.DIRECTORY_SEPARATOR;
                break;
        }

        return 'media'.DIRECTORY_SEPARATOR.$modelPath.md5($media->id.config('app.key')).DIRECTORY_SEPARATOR;
    }

    /**
     * Get the path for conversions of the given media, relative to the root storage path.
     */
    public function getPathForConversions(Media $media): string
    {
        return md5($media->id.config('app.key')).'/conversions';
    }

    /**
     * Get the path for responsive images of the given media, relative to the root storage path.
     */
    public function getPathForResponsiveImages(Media $media): string
    {
        return md5($media->id.config('app.key')).'/responsive-images/';
    }

}