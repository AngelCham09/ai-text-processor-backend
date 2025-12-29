<?php

use App\Services\TextProcessors\ImproveTextProcessor;

it('capitalizes first letter and trims text', function () {
    $processor = new ImproveTextProcessor();

    $text = '  hello world  ';
    $result = $processor->process($text);

    expect($result)->toBe("Hello world");
});
