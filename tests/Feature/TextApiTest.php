<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns processed text for valid request', function () {
    $response = $this->postJson('/api/process-text', [
        'text' => 'hello world',
        'action' => 'improve',
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'data' => [
                'result' => 'Hello world'
            ]
        ]);
});


it('returns validation error for missing text', function () {
    $response = $this->postJson('/api/process-text', [
        'action' => 'improve',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['text']);
});
