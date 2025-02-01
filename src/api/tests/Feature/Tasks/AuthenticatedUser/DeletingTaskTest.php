<?php

use App\Models\Task;
use App\Models\User;

test('removing a task receive 204 response', function() {
    $user = User::factory()->create();
    $task = Task::factory()->create();

    $user->tasks()->save($task);

    $token = $user->createToken($user->email)->plainTextToken;
    
    $response = $this->withHeaders([
        'Authorization' => "Bearer {$token}"
    ])
    ->deleteJson("/api/tasks/{$task->id}");

    $response->assertStatus(204);
});

test('removing a non-existent task receive 404 response', function() {
    $user = User::factory()->create();
    $token = $user->createToken($user->email)->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => "Bearer {$token}"
    ])
    ->deleteJson("/api/tasks/999");

    $response->assertStatus(404);
});

test('removing a task I do not own receive 401 response', function() {
    $userOwnerOfTask = User::factory()->create();
    $otherUser = User::factory()->create();

    $task = Task::factory()->create();
    $userOwnerOfTask->tasks()->save($task);

    $otherUserToken = $otherUser->createToken($otherUser->email)->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => "Bearer {$otherUserToken}"
    ])
    ->deleteJson("/api/tasks/{$task->id}");

    $response->assertStatus(401);
});
