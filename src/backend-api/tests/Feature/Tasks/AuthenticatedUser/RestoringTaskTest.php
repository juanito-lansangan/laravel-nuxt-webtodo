<?php

use App\Models\Task;
use App\Models\User;

test('restoring a task receive 200 response with updated task data', function() {
    $user = User::factory()->create();
    $task = Task::factory()->archived()->create();

    $user->tasks()->save($task);
    $token = $user->createToken($user->email)->plainTextToken;

    $archivedAt = $task->archived_at->format('Y-m-d');
    expect($archivedAt)->toBe(now()->format('Y-m-d'));

    $response = $this->withHeaders([
      'Authorization'  => "Bearer {$token}"
    ])
    ->patchJson("/api/tasks/{$task->id}/restore");

    $responseTask = $response->json();
    $archivedAt = $responseTask['archived_at'] ;

    // at this point the archived_at should be null
    expect($archivedAt)->toBeNull();

    $response
        ->assertStatus(200)
        ->assertJson($responseTask);
});

test('restoring a non-existent task receive 404 response', function() {
    $user = User::factory()->create();

    $token = $user->createToken($user->email)->plainTextToken;

    $response = $this->withHeaders([
      'Authorization'  => "Bearer {$token}"
    ])
    ->patchJson("/api/tasks/999/restore");

    $response
        ->assertStatus(404);
});

test('restoring a task that I do not own receive 401 response', function() {
    $userOwnerOfTask = User::factory()->create();
    $otherUser = User::factory()->create();

    $task = Task::factory()->archived()->create();
    $userOwnerOfTask->tasks()->save($task);

    $otherUserToken = $otherUser->createToken($otherUser->email)->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => "Bearer {$otherUserToken}"
    ])
    ->patchJson("/api/tasks/{$task->id}/restore");

    $response->assertStatus(401);
});