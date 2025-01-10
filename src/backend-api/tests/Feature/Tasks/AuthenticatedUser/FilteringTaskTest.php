<?php

use App\Enums\TaskPriority;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Carbon;

test('filtering no tasks receive 200 response with empty array', function() {
    $user = User::factory()->create();
    $token = $user->createToken($user->email)->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => "Bearer {$token}"
    ])
    ->getJson('/api/tasks?date_filter=created_at&date_from=2025-01-01&date_to=2025-01-20');

    $response
        ->assertStatus(200)
        ->assertJson([]);
});

test('filtering tasks with completed date receive 200 response with array of data', function() {
    $userWithSession = User::factory()->create();

    $task1 = Task::factory()->create([
        'completed_at' => '2025-01-08'
    ]);

    $task2 = Task::factory()->create([
        'completed_at' => '2025-01-09'
    ]);

    $task3 = Task::factory()->create([
        'completed_at' => '2025-01-04'
    ]);

    $userWithSession->tasks()->saveMany([$task1, $task2, $task3]);

    // this tasks should not included on the results
    $tasksNotFiltered = Task::factory(5)->create([
        'completed_at' => '2024-12-01'
    ]);
    $userWithSession->tasks()->saveMany($tasksNotFiltered);

    $userWithSessionToken = $userWithSession->createToken($userWithSession->email)->plainTextToken;

    $otherUser = User::factory()->create();
    $otherUserTasks = Task::factory(2)->create();
    $otherUser->tasks()->saveMany($otherUserTasks);

    expect($userWithSession->tasks()->count())->toBe(8);
    expect($otherUser->tasks()->count())->toBe(2);

    $response = $this->withHeaders([
        'Authorization' => "Bearer {$userWithSessionToken}"
    ])
    ->getJson("/api/tasks?date_filter=completed_at&date_from=2025-01-01&date_to=2025-01-31");
    
    $filteredTasks = $response->json('data');

    $first = Carbon::parse($filteredTasks[0]['completed_at'])->format('Y-m-d');
    $second = Carbon::parse($filteredTasks[1]['completed_at'])->format('Y-m-d');
    $third = Carbon::parse($filteredTasks[2]['completed_at'])->format('Y-m-d');

    // should be descending order of description
    expect($filteredTasks)->toHaveCount(3);
    expect($first)->toBe('2025-01-08');
    expect($second)->toBe('2025-01-09');
    expect($third)->toBe('2025-01-04');

    $response->assertStatus(200);
});

test('filtering tasks with due date receive 200 response with array of data', function() {
    $userWithSession = User::factory()->create();

    $task1 = Task::factory()->create([
        'due_date' => '2025-01-08'
    ]);

    $task2 = Task::factory()->create([
        'due_date' => '2025-01-09'
    ]);

    $task3 = Task::factory()->create([
        'due_date' => '2025-01-04'
    ]);

    $userWithSession->tasks()->saveMany([$task1, $task2, $task3]);

    // this tasks should not included on the results
    $tasksNotFiltered = Task::factory(5)->create([
        'due_date' => '2024-12-01'
    ]);
    $userWithSession->tasks()->saveMany($tasksNotFiltered);

    $userWithSessionToken = $userWithSession->createToken($userWithSession->email)->plainTextToken;

    $otherUser = User::factory()->create();
    $otherUserTasks = Task::factory(2)->create();
    $otherUser->tasks()->saveMany($otherUserTasks);

    expect($userWithSession->tasks()->count())->toBe(8);
    expect($otherUser->tasks()->count())->toBe(2);

    $response = $this->withHeaders([
        'Authorization' => "Bearer {$userWithSessionToken}"
    ])
    ->getJson("/api/tasks?date_filter=due_date&date_from=2025-01-01&date_to=2025-01-31");
    
    $filteredTasks = $response->json('data');

    $first = Carbon::parse($filteredTasks[0]['due_date'])->format('Y-m-d');
    $second = Carbon::parse($filteredTasks[1]['due_date'])->format('Y-m-d');
    $third = Carbon::parse($filteredTasks[2]['due_date'])->format('Y-m-d');

    // should be descending order of description
    expect($filteredTasks)->toHaveCount(3);
    expect($first)->toBe('2025-01-08');
    expect($second)->toBe('2025-01-09');
    expect($third)->toBe('2025-01-04');

    $response->assertStatus(200);
});

test('filtering tasks with archived date receive 200 response with array of tasks', function() {
    $userWithSession = User::factory()->create();

    $task1 = Task::factory()->create([
        'archived_at' => '2025-01-08'
    ]);

    $task2 = Task::factory()->create([
        'archived_at' => '2025-01-09'
    ]);

    $task3 = Task::factory()->create([
        'archived_at' => '2025-01-04'
    ]);

    $userWithSession->tasks()->saveMany([$task1, $task2, $task3]);

    // this tasks should not included on the results
    $tasksNotFiltered = Task::factory(5)->create([
        'archived_at' => '2024-12-01'
    ]);
    $userWithSession->tasks()->saveMany($tasksNotFiltered);

    $userWithSessionToken = $userWithSession->createToken($userWithSession->email)->plainTextToken;

    $otherUser = User::factory()->create();
    $otherUserTasks = Task::factory(2)->create();
    $otherUser->tasks()->saveMany($otherUserTasks);

    expect($userWithSession->tasks()->count())->toBe(8);
    expect($otherUser->tasks()->count())->toBe(2);

    $response = $this->withHeaders([
        'Authorization' => "Bearer {$userWithSessionToken}"
    ])
    ->getJson("/api/tasks?date_filter=archived_at&date_from=2025-01-01&date_to=2025-01-31");
    
    $filteredTasks = $response->json('data');

    $first = Carbon::parse($filteredTasks[0]['archived_at'])->format('Y-m-d');
    $second = Carbon::parse($filteredTasks[1]['archived_at'])->format('Y-m-d');
    $third = Carbon::parse($filteredTasks[2]['archived_at'])->format('Y-m-d');

    // should be descending order of description
    expect($filteredTasks)->toHaveCount(3);
    expect($first)->toBe('2025-01-08');
    expect($second)->toBe('2025-01-09');
    expect($third)->toBe('2025-01-04');

    $response->assertStatus(200);
});

test('filtering tasks by priority level receive 200 response with array of tasks', function() {
    $userWithSession = User::factory()->create();

    $urgentTasks = Task::factory(10)->create([
        'priority' => TaskPriority::Urgent
    ]);
    $userWithSession->tasks()->saveMany($urgentTasks);

    $highTasks = Task::factory(2)->create([
        'priority' => TaskPriority::High
    ]);
    $userWithSession->tasks()->saveMany($highTasks);

    $normalTasks = Task::factory(2)->create([
        'priority' => TaskPriority::Normal
    ]);
    $userWithSession->tasks()->saveMany($normalTasks);

    $lowTasks = Task::factory(2)->create([
        'priority' => TaskPriority::Low
    ]);
    $userWithSession->tasks()->saveMany($lowTasks);

    $userWithSessionToken = $userWithSession->createToken($userWithSession->email)->plainTextToken;

    $otherUser = User::factory()->create();
    $otherUserTasks = Task::factory(2)->create();
    $otherUser->tasks()->saveMany($otherUserTasks);

    expect($userWithSession->tasks()->count())->toBe(16);
    expect($otherUser->tasks()->count())->toBe(2);

    $urgent = TaskPriority::Urgent->value;
    $response = $this->withHeaders([
        'Authorization' => "Bearer {$userWithSessionToken}"
    ])
    ->getJson("/api/tasks?priority={$urgent}");
    
    $filteredTasks = $response->json('data');
    
    foreach ($filteredTasks as $task) {
        expect($task['priority'])->toBe(TaskPriority::Urgent->value);
    }

    $response->assertStatus(200);
});

test('search tasks by title, description receive 200 response with array of tasks', function() {
    $userWithSession = User::factory()->create();

    $task1 = Task::factory()->create([
        'title' => 'Apple title task #1'
    ]);

    $task2 = Task::factory()->create([
        'title' => 'Apple title task #2'
    ]);

    $task3 = Task::factory()->create([
        'title' => 'Apple title task #3'
    ]);

    // this should not be included on search result
    $task4 = Task::factory()->create([
        'title' => 'Tomato title task'
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
    ->getJson("/api/tasks?search=apple");
    
    $searchTasks = $response->json('data');

    // should return only 3 items that contain apple on the title
    expect($searchTasks)->toHaveCount(3);
    expect($searchTasks[0]['title'])->toBe('Apple title task #1');
    expect($searchTasks[1]['title'])->toBe('Apple title task #2');
    expect($searchTasks[2]['title'])->toBe('Apple title task #3');

    $response->assertStatus(200);
});