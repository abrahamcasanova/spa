<?php

namespace App\Enums;

enum Status : string
{
    case active = 'active';
    case inactive = 'inactive';

    public static function active(): string
    {
        return self::active->value;
    }

    public static function inactive(): string
    {
        return self::inactive->value;
    }

    public static function all(): array
    {
        return [
            self::active->value,
            self::inactive->value,
        ];
    }
}
