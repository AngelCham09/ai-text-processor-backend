<?php

namespace App\Services\TextProcessors;

use App\Enums\TextActionType;
use InvalidArgumentException;

class TextProcessorFactory
{
    public static function create(TextActionType $action): TextProcessorInterface
    {
        return match($action){
            TextActionType::IMPROVE => app(ImproveTextProcessor::class),
            TextActionType::SUMMARIZE => app(SummarizeTextProcessor::class),
            TextActionType::PROFESSIONAL => app(MakeProfessionalProcessor::class),
            TextActionType::BULLET => app(BulletPointsProcessor::class),
            default => throw new InvalidArgumentException("Invalid action type")
        };
    }
}
