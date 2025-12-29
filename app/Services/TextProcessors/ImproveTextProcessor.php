<?php

namespace App\Services\TextProcessors;

class ImproveTextProcessor implements TextProcessorInterface
{
    public function process(string $text): string
    {
        //ucfirst => Converts the first character of the string to uppercase
        $text = ucfirst(trim($text));
        $text = preg_replace('/\s+/', ' ', $text);
        return $text;
    }
}


