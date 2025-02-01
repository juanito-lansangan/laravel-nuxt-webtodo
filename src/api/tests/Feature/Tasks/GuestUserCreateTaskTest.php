<?php

use App\Models\Task;

test('creating a task as a guest user receive 401 response', function() {
    $response = $this->postJson('/api/tasks', [
        'title' => 'new task',
        'description' => 'test description',
    ]);

    $response->assertStatus(401);
});

test('updating a task as a guest user receive 401 response', function() {
    $task = Task::factory()->create();

    $response = $this->putJson("/api/tasks/{$task->id}", [
        'title' => 'new task',
        'description' => 'test description',
    ]);

    $response->assertStatus(401);
});

test('viewing a task as a guest user receive 401 response', function() {
    $task = Task::factory()->create();

    $response = $this->getJson("/api/tasks/{$task->id}");

    $response->assertStatus(401);
});

test('deleting a task as a guest user receive 401 response', function() {
    $task = Task::factory()->create();

    $response = $this->deleteJson("/api/tasks/{$task->id}");

    $response->assertStatus(401);
});