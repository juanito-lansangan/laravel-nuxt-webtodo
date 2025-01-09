<?php

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Carbon;

test('filtering no tasks receive 200 response with empty array', function() {
    $user = User::factory()->create();
    $token = $user->createToken($user->email)->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => "Bearer {$token}"
    ])
    ->getJson('/api/tasks?completed_at_from=2025-01-01&completed_at_to=2025-01-20');

    $response
        ->assertStatus(200)
        ->assertJson([]);
});

/* test('filtering tasks with completed date receive 200 response with array of data', function() {
    $userWithSession = User::factory()->create();

    $task1 = Task::factory()->create([
        'completed_at' => now()->addDays(2)->format('Y-m-d')
    ]);

    $task2 = Task::factory()->create([
        'completed_at' => now()->addDays(1)->format('Y-m-d')
    ]);

    $task3 = Task::factory()->create([
        'completed_at' => now()->addDays(3)->format('Y-m-d')
    ]);

    $userWithSession->tasks()->saveMany([$task1, $task2, $task3]);

    $userWithSessionToken = $userWithSession->createToken($userWithSession->email)->plainTextToken;

    $otherUser = User::factory()->create();
    $otherUserTasks = Task::factory(2)->create();
    $otherUser->tasks()->saveMany($otherUserTasks);

    expect($userWithSession->tasks()->count())->toBe(3);
    expect($otherUser->tasks()->count())->toBe(2);

    $dateFrom = now()->format('Y-m-d');
    $dateTo = now()->addDays(3)->format('Y-m-d');

    $response = $this->withHeaders([
        'Authorization' => "Bearer {$userWithSessionToken}"
    ])
    ->getJson("/api/tasks?completed_at_from={$dateFrom}&completed_at_to={$dateTo}");
    
    $sortedTasks = $response->json('data');

    $first = Carbon::parse($sortedTasks[0]['completed_at'])->format('Y-m-d');
    $second = Carbon::parse($sortedTasks[1]['completed_at'])->format('Y-m-d');
    $third = Carbon::parse($sortedTasks[2]['completed_at'])->format('Y-m-d');

    // should be descending order of description
    expect($first)->toBe('2025-01-12');
    expect($second)->toBe('2025-01-11');
    expect($third)->toBe('2025-01-10');

    $response->assertStatus(200);
}); */