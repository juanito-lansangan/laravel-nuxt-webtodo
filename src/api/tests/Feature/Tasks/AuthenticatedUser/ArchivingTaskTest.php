<?php

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Carbon;

test('archiving a task receive 200 response with the updated task data', function() {
    $user = User::factory()->create();
    $task = Task::factory()->create();
    $user->tasks()->save($task);

    $token = $user->createToken($user->email)->plainTextToken;

    // archive date should be null upon creation
    expect($task->archived_at)->toBeNull();

    $response = $this->withHeaders([
        'Authorization' => "Bearer {$token}"
    ])
    ->patchJson("/api/tasks/{$task->id}/archive");

    $responseTask = $response->json();

    // archive date should be the current date
    $archivedAt = Carbon::parse($responseTask['data']['archived_at'])->format('Y-m-d');

    expect($archivedAt)->toBe(now()->format('Y-m-d'));

    $response
        ->assertStatus(200)
        ->assertJson($responseTask);
});

test('archiving a task that I do not own receive 401 response', function() {
    $userOwnerOfTask = User::factory()->create();
    $otherUser = User::factory()->create();

    $task = Task::factory()->create();
    $userOwnerOfTask->tasks()->save($task);

    $otherUserToken = $otherUser->createToken($otherUser->email)->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => "Bearer {$otherUserToken}"
    ])
    ->patchJson("/api/tasks/{$task->id}/archive");

    $response->assertStatus(401);
});

