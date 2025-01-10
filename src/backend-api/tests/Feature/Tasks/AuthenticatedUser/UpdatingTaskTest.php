<?php

use App\Enums\TaskPriority;
use App\Models\Tag;
use App\Models\Task;
use App\Models\User;

test('editing a task with valid inputs receive 200 response with updated task data', function() {
    $user = User::factory()->create();
    $task = Task::factory()->create();

    $user->tasks()->save($task);
    $token = $user->createToken($user->email)->plainTextToken;

    $response = $this
    ->withHeaders([
        'Authorization' => "Bearer {$token}"
    ])
    ->putJson("/api/tasks/{$task->id}", [
        'title' => 'update task title',
        'description' => 'test update description',
        'due_date' => now()->addDays(5)->format('Y-m-d'),
        'priority' => TaskPriority::Normal
    ]);

    $responseTask = $response->json();

    expect($task->id)->toBe($responseTask['id']);

    $response
        ->assertStatus(200)
        ->assertJson($responseTask);
});

test('updating a task as an authenticated user with tags receive 200 response', function() {
    $user = User::factory()->create();
    $token = $user->createToken($user->email)->plainTextToken;

    $task = Task::factory()->create();
    $user->tasks()->save($task);

    $tags = Tag::factory(3)->create();
    $tagsId = $tags->pluck('id')->toArray();

    $response = $this->withHeaders([
        'Authorization' => "Bearer {$token}",
    ])
    ->putJson("/api/tasks/{$task->id}", [
        'title' => 'update task',
        'description' => 'update description',
        'tags' => $tagsId
    ]);

    $responseTask = $response->json();
    expect($responseTask['tags'])->toHaveCount(3);

    $response
        ->assertStatus(200)
        ->assertJson($responseTask);
});

test('editing a task with invalid inputs receive 422 response with error message', function() {
    $user = User::factory()->create();
    $task = Task::factory()->create();

    $user->tasks()->save($task);

    $token = $user->createToken($user->email)->plainTextToken;
    $response = $this->withHeaders([
        'Authorization' => "Bearer {$token}"
    ])
    ->putJson("/api/tasks/{$task->id}", []);

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

test('editing a non-existent task receive 404 response', function() {
    $user = User::factory()->create();
    $token = $user->createToken($user->email)->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => "Bearer {$token}"
    ])
    ->putJson("/api/tasks/999");

    $response->assertStatus(404);
});

test('editing a task I do not own receive 401 response', function() {
    $userOwnerOfTask = User::factory()->create();
    $otherUser = User::factory()->create();

    // create a task and assign to the user owner
    $task = Task::factory()->create();
    $userOwnerOfTask->tasks()->save($task);

    // create a token of other user
    $otherUsertoken = $otherUser->createToken($otherUser->email)->plainTextToken;

    // then access the task that he didn't own
    $response = $this
        ->withHeaders([
            'Authorization' => "Bearer {$otherUsertoken}"
        ])
        ->putJson("/api/tasks/{$task->id}", [
            'title' => 'update task title',
            'description' => 'test update description',
            'due_date' => now()->addDays(5)->format('Y-m-d'),
            'priority' => TaskPriority::Normal
        ]);

    $response->assertStatus(401);
});