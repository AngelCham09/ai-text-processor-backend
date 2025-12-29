<?php

use App\Services\TextProcessors\SummarizeTextProcessor;

it('summarizes text to first two sentences', function () {
    $processor = new SummarizeTextProcessor();

    $text = "This is the first sentence. This is the second sentence. This is the third sentence.";
    $result = $processor->process($text);

    expect($result)->toBe("This is the first sentence. This is the second sentence.");
});
