<?php

use App\Models\User;

test('logged in user should able to logout and receive 204 response', function() {
    $user = User::factory()->create();
    $token = $user->createToken('TestToken')->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->postJson('/api/logout/');

    $response->assertStatus(204);
});