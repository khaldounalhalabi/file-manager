<?php

namespace App\Enums;

enum FileStatusEnum: string
{
    case LOCKED = "locked";
    case UNLOCKED = "unlocked";

    public static function getAllValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
