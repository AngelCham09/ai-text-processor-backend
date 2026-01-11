<?php

namespace App\Services\TextProcessors;

use App\Services\AI\GeminiService;

class ImproveTextProcessor implements TextProcessorInterface
{
    public function __construct(private GeminiService $gemini) {}

    public function process(string $text): string
    {
        //ucfirst => Converts the first character of the string to uppercase
        $text = ucfirst(trim($text));
        $text = preg_replace('/\s+/', ' ', $text);
        return $text;
    }

    public function processAI(string $text): string
    {
        $systemRule = "You are a precise Proofreader. Correct all spelling, grammar, and punctuation. IMPORTANT: Return ONLY the corrected text. Do not provide any conversational filler, explanations, or introductory remarks.";

        return $this->gemini->generate($text, $systemRule) ?: $text;
    }
}


