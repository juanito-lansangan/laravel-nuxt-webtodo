<?php

use App\Models\Task;
use App\Models\User;

test('marking a task as inprogress receive 200 response with the updated task data', function() {
    $user = User::factory()->create();
    $task = Task::factory()->completed()->create();

    $user->tasks()->save($task);

    $completedAt = $task->completed_at->format('Y-m-d');

    // created task from the factory should have completed_at
    expect($completedAt)->toBe(now()->format('Y-m-d'));

    $token = $user->createToken($user->email)->plainTextToken;
    $response = $this->withHeaders([
        'Authorization' => "Bearer {$token}"
    ])
    ->patchJson("/api/tasks/{$task->id}/inprogress");

    $responseTask = $response->json();

    // completed_at should be null at this point
    expect($responseTask['data']['completed_at'])->toBeNull();

    $response
        ->assertStatus(200)
        ->assertJson($responseTask);
});

test('marking a task as inprogress that I do not own receive 401 response', function() {
    $userOwnerOfTask = User::factory()->create();
    $otherUser = User::factory()->create();

    $task = Task::factory()->create();
    $userOwnerOfTask->tasks()->save($task);

    $otherUserToken = $otherUser->createToken($otherUser->email)->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => "Bearer {$otherUserToken}"
    ])
    ->patchJson("/api/tasks/{$task->id}/inprogress");

    $response->assertStatus(401);
});