<?php

use App\Models\User;

test('signup with valid email address and password receive 200 response', function() {

    $response = $this->postJson('/api/register', [
        'email' => 'johndoe@mail.com',
        'name' => 'John Doe',
        'password' => 'Secret123!',
        'password_confirmation' => 'Secret123!',
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

test('signup with invalid email address and password receive 422 response', function() {

    $response = $this->postJson('/api/register', [
        'email' => 'notemail',
        'password' => '',
    ]);

    // $response->assertStatus(422);
    $response
        ->assertStatus(422)
        ->assertJson([
            'message' => 'The name field is required. (and 2 more errors)',
            'errors' => [
                "name" => [
                    "The name field is required."
                ],
                "email" => [
                    "The email field must be a valid email address."
                ],
                "password" => [
                    "The password field is required."
                ]
            ],
        ]);
});