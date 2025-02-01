<?php

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Carbon;

test('adding a valid due date to a task receive 200 response with the updated task data', function() {
    $user = User::factory()->create();
    $task = Task::factory()->create();
    $user->tasks()->save($task);

    $token = $user->createToken($user->email)->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => "Bearer {$token}"
    ])
    ->putJson("/api/tasks/{$task->id}", [
        'title' => 'new task',
        'description' => 'test description',
        'due_date' => now()->format('Y-m-d'),
    ]);

    $responseTask = $response->json();
    $dueDate = Carbon::parse($responseTask['data']['due_date'])->format('Y-m-d');

    expect($dueDate)->toBe(now()->format('Y-m-d'));

    $response
        ->assertStatus(200)
        ->assertJson($responseTask);
});

test('optional due date to a task receive 200 response with the updated task data', function() {
    $user = User::factory()->create();
    $task = Task::factory()->create();
    $user->tasks()->save($task);

    $token = $user->createToken($user->email)->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => "Bearer {$token}"
    ])
    ->putJson("/api/tasks/{$task->id}", [
        'title' => 'new task',
        'description' => 'test description',
    ]);

    $responseTask = $response->json();

    $response
        ->assertStatus(200)
        ->assertJson($responseTask);
});

test('setting invalid due date to a task must receive 422 response with the error message', function() {
    $user = User::factory()->create();
    $task = Task::factory()->create();
    $user->tasks()->save($task);

    $token = $user->createToken($user->email)->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => "Bearer {$token}"
    ])
    ->putJson("/api/tasks/{$task->id}", [
        'title' => 'new task',
        'description' => 'test description',
        'due_date' => 'abc-d'
    ]);

    $response
        ->assertStatus(422)
        ->assertJson([
            "message" => "The due date field must be a valid date. (and 2 more errors)",
            "errors" => [
                "due_date" => [
                    "The due date field must be a valid date.",
                    "The due date field must match the format Y-m-d.",
                    "The due date field must be a date after or equal to today."
                ]
            ]
        ]);
});

test('setting old value to due date to a task must receive 422 response with the error message', function() {
    $user = User::factory()->create();
    $task = Task::factory()->create();
    $user->tasks()->save($task);

    $token = $user->createToken($user->email)->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => "Bearer {$token}"
    ])
    ->putJson("/api/tasks/{$task->id}", [
        'title' => 'update task',
        'description' => 'test description',
        'due_date' => now()->subDays(2)->format('Y-m-d')
    ]);

    $response
        ->assertStatus(422)
        ->assertJson([
            "message" => "The due date field must be a date after or equal to today.",
            "errors" => [
                "due_date" => [
                    "The due date field must be a date after or equal to today."
                ]
            ]
        ]);
});

test('adding due date to a non-existent task receive 404 response', function() {
    $user = User::factory()->create();
    $token = $user->createToken($user->email)->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => "Bearer {$token}"
    ])
    ->putJson("/api/tasks/999", [
        'title' => 'update task',
        'description' => 'test description',
        'due_date' => now()->format('Y-m-d')
    ]);

    $response->assertStatus(404);
});