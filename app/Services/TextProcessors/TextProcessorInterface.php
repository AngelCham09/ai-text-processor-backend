<?php

namespace App\Services\TextProcessors;

interface TextProcessorInterface
{
    public function process(string $text): string;
}

