<?php

use App\Models\User;
use Laravel\Sanctum\Sanctum;

test('login existing email address and password receive 200 response', function() {

    $user = User::factory()->create();

    $response = $this->postJson('/api/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertStatus(200);
});

test('login with invalid email address and/or password receive 422 response', function() {
    $response = $this->postJson('/api/login', [
        'email' => '',
        'password' => '',
    ]);

    $response->assertStatus(422);
});

test('login with non-existing email address receive 404 response', function() {
    $response = $this->postJson('/api/login', [
        'email' => 'invalid@mail.com',
        'password' => 'password',
    ]);

    $response->assertStatus(404);
});