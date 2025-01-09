<?php

use App\Enums\TaskPriority;
use App\Models\Task;
use App\Models\User;

test('creating a task as an authenticated user with valid title, description, due date, and priority receive 200 response', function() {
    $user = User::factory()->create();
    $token = $user->createToken($user->email)->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => "Bearer {$token}",
    ])
    ->postJson('/api/tasks', [
        'title' => 'new task',
        'description' => 'test description',
        'due_date' => now()->addDays(5)->format('Y-m-d'),
        'priority' => TaskPriority::Urgent
    ]);

    $responseTask = $response->json();

    $response
        ->assertStatus(200)
        ->assertJson($responseTask);
});

test('creating a task as an authenticated user with invalid title and/or description receive 422 response', function() {

    $user = User::factory()->create();
    $token = $user->createToken($user->email)->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => "Bearer {$token}"
    ])
    ->postJson('/api/tasks', []);

    $response
        ->assertStatus(422)
        ->assertJson([
            'message' => 'The title field is required. (and 1 more error)',
            'errors' => [
                "title" => [
                    "The title field is required."
                ],
                "description" => [
                    "The description field is required."
                ]
            ],
        ]);
});