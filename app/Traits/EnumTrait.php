<?php

namespace App\Traits;

trait EnumTrait
{
    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }

    public static function cases(): array
    {
        return array_map(fn ($case, $value) => $case, $value);
    }
}
