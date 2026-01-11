<?php

namespace App\Services\TextProcessors;

use App\Services\AI\GeminiService;

class MakeProfessionalProcessor implements TextProcessorInterface
{
    public function __construct(private GeminiService $gemini) {}

    public function process(string $text): string
    {
        $replace = [
            "I'm" => "I am",
            "don't" => "do not",
            "can't" => "cannot",
        ];
        return str_replace(array_keys($replace), array_values($replace), $text);
    }

    public function processAI(string $text): string
    {
        $systemRule = "You are a Corporate Communications Expert. Rewrite the input to be professional, clear, and formal. IMPORTANT: Output ONLY the rewritten text. Do not say 'Here is the professional version' or include any other comments.";

        return $this->gemini->generate($text, $systemRule) ?: $text;
    }
}
