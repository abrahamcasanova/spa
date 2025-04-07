<?php

namespace App\Enums;

enum UserRoles : string
{
    case admin = 'admin';
    case user = 'user';
    case other = 'other';
    case partner = 'partner';

    public static function admin(): string
    {
        return self::admin->value;
    }

    public static function user(): string
    {
        return self::user->value;
    }

    public static function other(): string
    {
        return self::other->value;
    }

    public static function partner(): string
    {
        return self::partner->value;
    }

    public static function all(): array
    {
        return [
            self::admin->value,
            self::user->value,
            self::other->value,
            self::partner->value,
        ];
    }
}
