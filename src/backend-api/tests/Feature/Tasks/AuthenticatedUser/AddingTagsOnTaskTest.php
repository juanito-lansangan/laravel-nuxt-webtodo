<?php

use App\Models\Tag;
use App\Models\Task;
use App\Models\User;

test('adding valid tags to a task receive 200 response with the task data that includes tags', function() {
    $user = User::factory()->create();
    $token = $user->createToken($user->email)->plainTextToken;

    $task = Task::factory()->create();
    $user->tasks()->save($task);

    $tags = Tag::factory(2)->create();
    $tagsId = $tags->pluck('id')->toArray();

    $response = $this->withHeaders([
        'Authorization' => "Bearer {$token}"
    ])
    ->patchJson("/api/tasks/{$task->id}/tags", [
        'tags' => $tagsId
    ]);

    $responseTask = $response->json();

    // we can expect that the reponse task have 2 tags
    $taskTags = $responseTask['data']['tags'];
    expect($taskTags)->toHaveCount(2);

    $response
        ->assertStatus(200)
        ->assertJson($responseTask);
});

test('adding invalid tags receive 422 with error messages', function () {
    $user = User::factory()->create();
    $token = $user->createToken($user->email)->plainTextToken;

    $task = Task::factory()->create();
    $user->tasks()->save($task);

    $response = $this->withHeaders([
        'Authorization' => "Bearer {$token}"
    ])
    ->patchJson("/api/tasks/{$task->id}/tags", [
        'tags'
    ]);

    $response
        ->assertStatus(422)
        ->assertJson([
            "message" => "The tags field is required.",
            "errors" => [
                "tags" => [
                    "The tags field is required."
                ]
            ]
        ]);
});

test('adding non-existing tags receive 422 with error messages', function () {
    $user = User::factory()->create();
    $token = $user->createToken($user->email)->plainTextToken;

    $task = Task::factory()->create();
    $user->tasks()->save($task);

    $response = $this->withHeaders([
        'Authorization' => "Bearer {$token}"
    ])
    ->patchJson("/api/tasks/{$task->id}/tags", [
        'tags' => [1,2,3]
    ]);

    $response
        ->assertStatus(422)
        ->assertJson([
            "message" => "The selected tags.0 is invalid. (and 2 more errors)",
            "errors" => [
                "tags.0" => [
                    "The selected tags.0 is invalid."
                ],
                "tags.1" => [
                    "The selected tags.1 is invalid."
                ],
                "tags.2" => [
                    "The selected tags.2 is invalid."
                ]
            ]
        ]);
});

test('adding tags to a non-existent task receive 404 response', function() {
    $user = User::factory()->create();
    $token = $user->createToken($user->email)->plainTextToken;

    $tags = Tag::factory(3)->create();
    $tagsId = $tags->pluck('id')->toArray();

    $response = $this->withHeaders([
        'Authorization' => "Bearer {$token}"
    ])
    ->patchJson("/api/tasks/999/tags", [
        'tags' => $tagsId
    ]);

    $response->assertStatus(404);
});

test('adding tags to a task I do not own receive 401 response', function() {
    $userOwnerOfTask = User::factory()->create();
    $otherUser = User::factory()->create();

    $task = Task::factory()->create();
    $userOwnerOfTask->tasks()->save($task);

    $otherUserToken = $otherUser->createToken($otherUser->email)->plainTextToken;

    $tags = Tag::factory(3)->create();
    $tagsId = $tags->pluck('id')->toArray();

    $response = $this->withHeaders([
        'Authorization' => "Bearer {$otherUserToken}"
    ])
    ->patchJson("/api/tasks/{$task->id}/tags", [
        'tags' => $tagsId
    ]);

    $response->assertStatus(401);
});