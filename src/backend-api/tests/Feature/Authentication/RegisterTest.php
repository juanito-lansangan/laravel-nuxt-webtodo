<?php

test('signup with valid email address and password receive 200 response', function() {

    $response = $this->postJson('/api/register', [
        'email' => 'johndoe@mail.com',
        'name' => 'John Doe',
        'password' => 'Secret123!',
        'password_confirmation' => 'Secret123!',
    ]);

    $response->assertStatus(200);
});

test('signup with invalid email address and password receive 422 response', function() {

    $response = $this->postJson('/api/register', [
        'email' => 'notemail',
        'password' => '',
    ]);

    $response->assertStatus(422);
});