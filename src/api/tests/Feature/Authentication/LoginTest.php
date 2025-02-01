<?php

use App\Models\User;

test('login existing email address and password receive 200 response with user data and a token', function() {

    $user = User::factory()->create();

    $response = $this->postJson('/api/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $user = $response->json('user');
    $token = $response->json('token');

    $response
        ->assertStatus(200)
        ->assertJson([
            'user' => $user,
            'token' => $token
        ]);
});

test('login with invalid email address and/or password receive 422 response', function() {
    $response = $this->postJson('/api/login', [
        'email' => '',
        'password' => '',
    ]);

    $response
        ->assertStatus(422)
        ->assertJson([
            'message' => 'The email field is required. (and 1 more error)',
            'errors' => [
                'email' => ['The email field is required.'],
                'password' => ['The password field is required.'],
            ],
        ]);
});

test('login with non-existing email address receive 404 response', function() {
    $response = $this->postJson('/api/login', [
        'email' => 'invalid@mail.com',
        'password' => 'password',
    ]);

    $response->assertStatus(404);
});