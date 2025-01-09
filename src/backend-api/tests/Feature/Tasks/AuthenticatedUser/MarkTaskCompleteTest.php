<?php

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Carbon;

test('marking a task as complete receive 200 response with updated task data', function() {
    $user = User::factory()->create();
    $task = Task::factory()->create();

    $user->tasks()->save($task);
    $token = $user->createToken($user->email)->plainTextToken;

    expect($task->completed_at)->toBeNull();

    $response = $this->withHeaders([
        'Authorization' => "Bearer {$token}"
    ])
    ->patchJson("/api/tasks/{$task->id}/complete");

    $responseTask = $response->json();

    // check if completed_at is date now
    $completedAt = Carbon::parse($responseTask['completed_at'])->format('Y-m-d');

    expect($task->id)->toBe($responseTask['id']);
    expect($completedAt)->toBe(now()->format('Y-m-d'));

    $response
        ->assertStatus(200)
        ->assertJson($responseTask);
});

test('marking a non-existent task as completed receive 404 response', function() {
    $user = User::factory()->create();
    $token = $user->createToken($user->email)->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => "Bearer {$token}"
    ])
    ->patchJson("/api/tasks/999/complete");

    $response->assertStatus(404);
});

test('marking a task as completed I do not own receive 401 response', function() {
    $userOwnerOfTask = User::factory()->create();
    $otherUser = User::factory()->create();

    $task = Task::factory()->create();
    $userOwnerOfTask->tasks()->save($task);

    $otherUserToken = $otherUser->createToken($otherUser->email)->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => "Bearer {$otherUserToken}"
    ])
    ->patchJson("/api/tasks/{$task->id}/complete");

    $response->assertStatus(401);
});