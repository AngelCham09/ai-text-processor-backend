<?php

namespace App\Services\TextProcessors;

use App\Services\AI\GeminiService;

class BulletPointsProcessor implements TextProcessorInterface
{

    public function __construct(private GeminiService $gemini) {}

    public function process(string $text): string
    {
        $sentences = preg_split('/(\.|\?|!)\s/', $text);
        return "- " . implode("\n- ", $sentences);
    }

    public function processAI(string $text): string
    {
        $systemRule = "You are an Information Architect. Convert the text into a clean Markdown bulleted list. IMPORTANT: Return ONLY the bulleted list. Do not add any conversational text before or after the points.";

        return $this->gemini->generate($text, $systemRule) ?: $text;
    }
}
