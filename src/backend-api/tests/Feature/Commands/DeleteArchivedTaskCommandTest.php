<?php

use App\Models\Task;

test('deleting archived tasks that is 1 week older return a successfull message', function () {
    
    // we can expect that this task is not deleted
    Task::factory()->create([
        'archived_at' => now()->subDay()
    ]);

    Task::factory(2)->create([
        'archived_at' => now()->subWeek()
    ]);

    Task::factory(5)->create([
        'archived_at' => now()->subWeeks(2)
    ]);

    $tasks = Task::all();

    expect($tasks)->toHaveCount(8);

    $this
        ->artisan('app:delete-archived-task')
        ->assertExitCode(0);

    $tasksAfterRunningCommand = Task::all();
    expect($tasksAfterRunningCommand)->toHaveCount(1);
});