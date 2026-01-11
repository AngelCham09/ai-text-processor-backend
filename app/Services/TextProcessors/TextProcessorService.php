<?php

namespace App\Services\TextProcessors;

use App\Enums\TextActionType;

class TextProcessorService
{
    public function process(string $text, TextActionType $action): string
    {
        $processor = TextProcessorFactory::create($action);
        return $processor->processAI($text);
    }

}
