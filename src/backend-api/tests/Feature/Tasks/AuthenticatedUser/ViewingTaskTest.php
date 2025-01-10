<?php

use App\Models\Task;
use App\Models\User;

test('viewing a single task as an aunthenticated user receive 200', function() {
    $user = User::factory()->create();
    $token = $user->createToken($user->email)->plainTextToken;

    $task = Task::factory()->create();
    $user->tasks()->save($task);

    $response = $this
        ->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])
        ->getJson("/api/tasks/{$task->id}");

    $responseTask = $response->json();

    expect($task->id)->toBe($responseTask['id']);
    
    $response
        ->assertStatus(200)
        ->assertJson($responseTask);
});

test('viewing non-existent task as an aunthenticated user receive 404', function() {
    $user = User::factory()->create();
    $token = $user->createToken($user->email)->plainTextToken;
    
    $response = $this
        ->withHeaders([
            'Authorization' => "Bearer {$token}"
        ])
        ->getJson("/api/tasks/999");

    $response->assertStatus(404);
});

test('viewing all tasks as an authenticated user receive 200 response with array of my tasks', function() {
    $user = User::factory()->create();
    $tasks = Task::factory(2)->create();

    $user->tasks()->saveMany($tasks);

    expect($tasks)->toHaveCount(2);

    $token = $user->createToken($user->email)->plainTextToken;

    $response = $this
    ->withHeaders([
        'Authorization' => "Bearer {$token}"
    ])
    ->getJson('/api/tasks');

    $responseTasks = $response->json('data');
    
    $response
        ->assertStatus(200);

    // check task response if user_id is the same that we created
    foreach ($responseTasks as $task) {
        expect($task['user_id'])->toBe($user->id);
    }

});

test('viewing all tasks as an authenticated user receive 200 response with empty array', function() {
    $user = User::factory()->create();
    $token = $user->createToken($user->email)->plainTextToken;

    $response = $this
        ->withHeaders([
            'Authorization' => "Bearer {$token}"
        ])
        ->getJson('/api/tasks');

    $responseTasks = $response->json('data');

    $response->assertStatus(200);

    expect($responseTasks)->toBeEmpty();
});

test('viewing a task that I did not create receive 401 response', function() {
    // create 2 users
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
        ->getJson("/api/tasks/{$task->id}");

    $response->assertStatus(401);
});