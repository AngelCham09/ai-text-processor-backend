<?php

namespace App\Services\TextProcessors;

use App\Services\AI\GeminiService;

class SummarizeTextProcessor implements TextProcessorInterface
{
    public function __construct(private GeminiService $gemini) {}

    public function process(string $text): string
    {
        $sentences = preg_split('/(\.|\?|!)\s/', $text);
        return implode('. ', array_slice($sentences, 0, 2)) . '.';
    }

    public function processAI(string $text): string
    {

        $systemRule = "You are an Executive Assistant. Provide a high-level summary of the input text. IMPORTANT: Output ONLY the summary. Do not include phrases like 'In summary' or 'Here is a brief overview'.";

        return $this->gemini->generate($text, $systemRule) ?: $text;

    }
}


