<?php

namespace App\Services\AI;

use Gemini\Data\Content;
use Gemini\Laravel\Facades\Gemini;
use Illuminate\Support\Facades\Log;

class GeminiService
{

    public function generate(string $prompt, string $systemInstruction = 'You are a helpful assistant.'): string
    {
        try {
            $result = Gemini::generativeModel(model: 'gemini-2.5-flash')
                ->withSystemInstruction(Content::parse($systemInstruction))
                ->generateContent($prompt);

            return $result->text() ?? '';
        } catch (\Throwable $e) {
            Log::error('Gemini exception', [
                'message' => $e->getMessage(),
            ]);
            return '';
        }
    }

}
