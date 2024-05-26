<?php

namespace App\Traits;

trait AvatarTrait
{
    public function avatarRemove(): void
    {
        $this->clearMediaCollection('avatar');
    }

    public function avatarUpload(): void
    {
        if (request()->hasFile('avatar')) {
            $this->addMediaFromRequest('avatar')->toMediaCollection('avatar');
        }
    }

    public function getAvatarAttribute(): string
    {
        return $this->getFirstMedia('avatar')?->getFullUrl() ?: url('/media/avatars/blank.png');
    }
}
