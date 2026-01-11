<?php

namespace App\Services\TextProcessors;

interface TextProcessorInterface
{
    public function process(string $text): string;

    public function processAI(string $text): string;
}

