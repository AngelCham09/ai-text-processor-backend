<?php

namespace App\Services\TextProcessors;

use App\Enums\TextActionType;
use InvalidArgumentException;

class TextProcessorFactory
{
    public static function create(TextActionType $action): TextProcessorInterface
    {
        return match($action){
            TextActionType::IMPROVE => new ImproveTextProcessor(),
            TextActionType::SUMMARIZE => new SummarizeTextProcessor(),
            TextActionType::PROFESSIONAL => new MakeProfessionalProcessor(),
            TextActionType::BULLET => new BulletPointsProcessor(),
            default => throw new InvalidArgumentException("Invalid action type")
        };
    }
}
