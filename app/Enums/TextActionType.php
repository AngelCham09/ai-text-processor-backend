<?php

namespace App\Enums;

enum TextActionType: string
{
    case IMPROVE = 'improve';
    case SUMMARIZE = 'summarize';
    case PROFESSIONAL = 'professional';
    case BULLET = 'bullet';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
