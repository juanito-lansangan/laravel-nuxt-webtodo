<?php

use App\Enums\TaskPriority;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Carbon;

test('sorting tasks by title receive 200 response with array of sorted tasks', function() {
    $userWithSession = User::factory()->create();

    $task1 = Task::factory()->create([
        'title' => 'Apple title task'
    ]);

    $task2 = Task::factory()->create([
        'title' => 'Crypto title task'
    ]);

    $task3 = Task::factory()->create([
        'title' => 'Beetle title task'
    ]);

    $userWithSession->tasks()->saveMany([$task1, $task2, $task3]);

    $userWithSessionToken = $userWithSession->createToken($userWithSession->email)->plainTextToken;

    $otherUser = User::factory()->create();
    $otherUserTasks = Task::factory(2)->create();
    $otherUser->tasks()->saveMany($otherUserTasks);

    expect($userWithSession->tasks()->count())->toBe(3);
    expect($otherUser->tasks()->count())->toBe(2);

    $response = $this->withHeaders([
        'Authorization' => "Bearer {$userWithSessionToken}"
    ])
    ->getJson("/api/tasks?sort_by=title&sort_order=desc");
    
    $sortedTasks = $response->json('data');

    // should be descending order of titles
    expect($sortedTasks[0]['title'])->toBe('Crypto title task');
    expect($sortedTasks[1]['title'])->toBe('Beetle title task');
    expect($sortedTasks[2]['title'])->toBe('Apple title task');

    $response->assertStatus(200);
});

test('sorting tasks by description receive 200 response with array of sorted tasks', function() {
    $userWithSession = User::factory()->create();

    $task1 = Task::factory()->create([
        'description' => 'Apple description task'
    ]);

    $task2 = Task::factory()->create([
        'description' => 'Crypto description task'
    ]);

    $task3 = Task::factory()->create([
        'description' => 'Beetle description task'
    ]);

    $userWithSession->tasks()->saveMany([$task1, $task2, $task3]);

    $userWithSessionToken = $userWithSession->createToken($userWithSession->email)->plainTextToken;

    $otherUser = User::factory()->create();
    $otherUserTasks = Task::factory(2)->create();
    $otherUser->tasks()->saveMany($otherUserTasks);

    expect($userWithSession->tasks()->count())->toBe(3);
    expect($otherUser->tasks()->count())->toBe(2);

    $response = $this->withHeaders([
        'Authorization' => "Bearer {$userWithSessionToken}"
    ])
    ->getJson("/api/tasks?sort_by=description&sort_order=desc");
    
    $sortedTasks = $response->json('data');

    // should be descending order of description
    expect($sortedTasks[0]['description'])->toBe('Crypto description task');
    expect($sortedTasks[1]['description'])->toBe('Beetle description task');
    expect($sortedTasks[2]['description'])->toBe('Apple description task');

    $response->assertStatus(200);
});

test('sorting tasks by due date receive 200 response with array of sorted tasks', function() {
    $userWithSession = User::factory()->create();

    $task1 = Task::factory()->create([
        'due_date' => '2022-01-10'
    ]);

    $task2 = Task::factory()->create([
        'due_date' => '2025-01-10'
    ]);

    $task3 = Task::factory()->create([
        'due_date' => '2025-01-04'
    ]);

    $userWithSession->tasks()->saveMany([$task1, $task2, $task3]);

    $userWithSessionToken = $userWithSession->createToken($userWithSession->email)->plainTextToken;

    $otherUser = User::factory()->create();
    $otherUserTasks = Task::factory(2)->create();
    $otherUser->tasks()->saveMany($otherUserTasks);

    expect($userWithSession->tasks()->count())->toBe(3);
    expect($otherUser->tasks()->count())->toBe(2);

    $response = $this->withHeaders([
        'Authorization' => "Bearer {$userWithSessionToken}"
    ])
    ->getJson("/api/tasks?sort_by=due_date&sort_order=desc");
    
    $sortedTasks = $response->json('data');
    $first = Carbon::parse($sortedTasks[0]['due_date'])->format('Y-m-d');
    $second = Carbon::parse($sortedTasks[1]['due_date'])->format('Y-m-d');
    $third = Carbon::parse($sortedTasks[2]['due_date'])->format('Y-m-d');

    // should be descending order of due date
    expect($first)->toBe('2025-01-10');
    expect($second)->toBe('2025-01-04');
    expect($third)->toBe('2022-01-10');

    $response->assertStatus(200);
});

test('sorting tasks by created date receive 200 response with array of sorted tasks', function() {
    $userWithSession = User::factory()->create();

    $task1 = Task::factory()->create([
        'created_at' => '2025-01-11'
    ]);

    $task2 = Task::factory()->create([
        'created_at' => '2025-01-10'
    ]);

    $task3 = Task::factory()->create([
        'created_at' => '2025-01-12'
    ]);

    $userWithSession->tasks()->saveMany([$task1, $task2, $task3]);

    $userWithSessionToken = $userWithSession->createToken($userWithSession->email)->plainTextToken;

    $otherUser = User::factory()->create();
    $otherUserTasks = Task::factory(2)->create();
    $otherUser->tasks()->saveMany($otherUserTasks);

    expect($userWithSession->tasks()->count())->toBe(3);
    expect($otherUser->tasks()->count())->toBe(2);

    $response = $this->withHeaders([
        'Authorization' => "Bearer {$userWithSessionToken}"
    ])
    ->getJson("/api/tasks?sort_by=created_at&sort_order=desc");
    
    $sortedTasks = $response->json('data');

    $first = Carbon::parse($sortedTasks[0]['created_at'])->format('Y-m-d');
    $second = Carbon::parse($sortedTasks[1]['created_at'])->format('Y-m-d');
    $third = Carbon::parse($sortedTasks[2]['created_at'])->format('Y-m-d');

    // should be descending order of created at
    expect($first)->toBe('2025-01-12');
    expect($second)->toBe('2025-01-11');
    expect($third)->toBe('2025-01-10');

    $response->assertStatus(200);
});

test('sorting tasks by completed date receive 200 response with array of sorted tasks', function() {
    $userWithSession = User::factory()->create();

    $task1 = Task::factory()->create([
        'completed_at' => '2024-10-26'
    ]);

    $task2 = Task::factory()->create([
        'completed_at' => '2025-01-10'
    ]);

    $task3 = Task::factory()->create([
        'completed_at' => '2024-12-26'
    ]);

    $userWithSession->tasks()->saveMany([$task1, $task2, $task3]);

    $userWithSessionToken = $userWithSession->createToken($userWithSession->email)->plainTextToken;

    $otherUser = User::factory()->create();
    $otherUserTasks = Task::factory(2)->create();
    $otherUser->tasks()->saveMany($otherUserTasks);

    expect($userWithSession->tasks()->count())->toBe(3);
    expect($otherUser->tasks()->count())->toBe(2);

    $response = $this->withHeaders([
        'Authorization' => "Bearer {$userWithSessionToken}"
    ])
    ->getJson("/api/tasks?sort_by=completed_at&sort_order=desc");
    
    $sortedTasks = $response->json('data');

    $first = Carbon::parse($sortedTasks[0]['completed_at'])->format('Y-m-d');
    $second = Carbon::parse($sortedTasks[1]['completed_at'])->format('Y-m-d');
    $third = Carbon::parse($sortedTasks[2]['completed_at'])->format('Y-m-d');

    // should be descending order of completed at
    expect($first)->toBe('2025-01-10');
    expect($second)->toBe('2024-12-26');
    expect($third)->toBe('2024-10-26');

    $response->assertStatus(200);
});

test('sorting tasks by priority level receive 200 response with array of sorted tasks', function() {
    $userWithSession = User::factory()->create();

    $task1 = Task::factory()->create([
        'priority' => TaskPriority::Normal
    ]);

    $task2 = Task::factory()->create([
        'priority' => TaskPriority::Urgent
    ]);

    $task3 = Task::factory()->create([
        'priority' => TaskPriority::High
    ]);

    $task4 = Task::factory()->create([
        'priority' => TaskPriority::Low
    ]);

    $userWithSession->tasks()->saveMany([$task1, $task2, $task3, $task4]);

    $userWithSessionToken = $userWithSession->createToken($userWithSession->email)->plainTextToken;

    $otherUser = User::factory()->create();
    $otherUserTasks = Task::factory(2)->create();
    $otherUser->tasks()->saveMany($otherUserTasks);

    expect($userWithSession->tasks()->count())->toBe(4);
    expect($otherUser->tasks()->count())->toBe(2);

    $response = $this->withHeaders([
        'Authorization' => "Bearer {$userWithSessionToken}"
    ])
    ->getJson("/api/tasks?sort_by=priority&sort_order=desc");
    
    $sortedTasks = $response->json('data');

    $first = $sortedTasks[0]['priority'];
    $second = $sortedTasks[1]['priority'];
    $third = $sortedTasks[2]['priority'];
    $fourth = $sortedTasks[3]['priority'];

    // should be descending order of priority level
    expect($first)->toBe(TaskPriority::Urgent->value);
    expect($second)->toBe(TaskPriority::High->value);
    expect($third)->toBe(TaskPriority::Normal->value);
    expect($fourth)->toBe(TaskPriority::Low->value);

    $response->assertStatus(200);
});

test('sorting no tasks  receive 200 response with empty array', function() {
    $userWithSession = User::factory()->create();

    $userWithSessionToken = $userWithSession->createToken($userWithSession->email)->plainTextToken;

    expect($userWithSession->tasks()->count())->toBe(0);

    $response = $this->withHeaders([
        'Authorization' => "Bearer {$userWithSessionToken}"
    ])
    ->getJson("/api/tasks?sort_by=title&sort_order=desc");
    
    $sortedTasks = $response->json('data');

    // should be descending order of titles
    $response
        ->assertStatus(200)
        ->assertJson([]);
});