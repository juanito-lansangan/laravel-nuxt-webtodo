<?php

use App\Models\Tag;
use App\Models\User;

test('creating tag receive 200 response with tag data', function() {
    $user = User::factory()->create();
    $token = $user->createToken($user->email)->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => "Bearer {$token}"
    ])
    ->postJson("/api/tags", [
        'name' => 'laravel'
    ]);

    $tag = Tag::first();
    expect($tag->name)->toBe('laravel');

    $response
        ->assertStatus(201);
});

test('creating invalid tag receive 422 response with error message', function() {
    $user = User::factory()->create();
    $token = $user->createToken($user->email)->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => "Bearer {$token}"
    ])
    ->postJson("/api/tags");

    $response
        ->assertStatus(422)
        ->assertJson([
            "message" => "The name field is required.",
            "errors" => [
                "name" => [
                    "The name field is required."
                ]
            ]
        ]);
});

test('view all tags receive 200 response with tags data', function() {
    $user = User::factory()->create();
    $token = $user->createToken($user->email)->plainTextToken;

    Tag::factory(5)->create();

    $response = $this->withHeaders([
        'Authorization' => "Bearer {$token}"
    ])
    ->getJson("/api/tags");

    $responseTags = $response->json('data');

    expect($responseTags)->toHaveCount(5);

    $response->assertStatus(200);
});

test('search tag receive 200 response with tag data', function() {
    $user = User::factory()->create();
    $token = $user->createToken($user->email)->plainTextToken;

    Tag::factory()->create([
        'name' => 'laravel'
    ]);

    Tag::factory()->create([
        'name' => 'vue'
    ]);

    Tag::factory()->create([
        'name' => 'laracon'
    ]);

    $response = $this->withHeaders([
        'Authorization' => "Bearer {$token}"
    ])
    ->getJson("/api/tags?search=lara");

    $responseTags = $response->json('data');

    expect($responseTags)->toHaveCount(2);

    $response->assertStatus(200);
});