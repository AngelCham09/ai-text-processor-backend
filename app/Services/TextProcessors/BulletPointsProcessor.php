<?php

namespace App\Services\TextProcessors;

class BulletPointsProcessor implements TextProcessorInterface
{
    public function process(string $text): string
    {
        $sentences = preg_split('/(\.|\?|!)\s/', $text);
        return "- " . implode("\n- ", $sentences);
    }
}
