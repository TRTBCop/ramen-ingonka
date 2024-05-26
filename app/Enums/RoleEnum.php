<?php

namespace App\Enums;


enum RoleEnum: string
{
    case SUPER = 'super';
    case MANAGER = 'manager';
    case CONTENTS = 'contents';
    case CS = 'cs';
    case OWNER = 'owner';

    public function text(): string
    {
        return $this->options()[$this->value] ?? '';
    }

    public static function options(): array
    {
        return [
            'super' => '최고관리자',
            'manager' => '운영자',
            'contents' => '콘텐츠',
            'cs' => '지원',
            'owner' => '원장선생님',
        ];
    }

    public static function admin(): array
    {
        return [
            self::SUPER,
            self::MANAGER,
            self::CONTENTS,
            self::CS,
        ];
    }
}
