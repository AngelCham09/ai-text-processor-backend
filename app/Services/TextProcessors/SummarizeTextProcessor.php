<?php

namespace App\Services\TextProcessors;

class SummarizeTextProcessor implements TextProcessorInterface
{
    public function process(string $text): string
    {
        $sentences = preg_split('/(\.|\?|!)\s/', $text);
        return implode('. ', array_slice($sentences, 0, 2)) . '.';
    }
}


