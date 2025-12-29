<?php

namespace App\Services\TextProcessors;

class MakeProfessionalProcessor implements TextProcessorInterface
{
    public function process(string $text): string
    {
        $replace = [
            "I'm" => "I am",
            "don't" => "do not",
            "can't" => "cannot",
        ];
        return str_replace(array_keys($replace), array_values($replace), $text);
    }
}
