<?php

namespace App\Services;

use App\Models\Academy;

class AcademyService
{
    private static Academy $academy;

    private static array $args;


    /**
     * @param Academy $academy
     * @param array $args
     */
    public function __construct(Academy $academy, array $args = [])
    {
        self::$academy = $academy;
        self::$args = $args;
    }


    public function logoUpload(): void
    {
        if (request()->hasFile('logo')) {
            $this->logoRemove();
            self::$academy->addMediaFromRequest('logo')->toMediaCollection('logo');
        }
    }

    public function logoRemove(): void
    {
        self::$academy->clearMediaCollection('logo');
    }

}
