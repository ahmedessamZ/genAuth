<?php

namespace App\Enums;

enum UserStatusEnum: int
{
    case PENDING = 2;
    case ACTIVE = 1;
    case INACTIVE = 0;

    public function getLabel(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::ACTIVE => 'Active',
            self::INACTIVE => 'Inactive',
        };
    }
}
