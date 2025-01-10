<?php

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('uploading valid files receive 200 response with task data that includes attachments', function() {
    $user = User::factory()->create();
    $token = $user->createToken($user->email)->plainTextToken;

    $task = Task::factory()->create();
    $user->tasks()->save($task);

    Storage::fake('avatars');

    $files = [
        UploadedFile::fake()->image('file1.png', 600, 600),
        UploadedFile::fake()->create('sample.mp4', '1000', 'mp4'),
        UploadedFile::fake()->create(fake()->word . '.doc', 100)
    ];
    
    $response = $this->withHeaders([
        'Authorization' => "Bearer {$token}"
    ])
    ->patchJson("/api/tasks/{$task->id}/attachments", [
        'attachments' => $files
    ]);

    $responseTask = $response->json('data');
    expect($responseTask['attachments'])->toHaveCount(3);

    $response->assertStatus(200);
});

test('uploading invalid files receive 422 response with error message', function() {
    $user = User::factory()->create();
    $token = $user->createToken($user->email)->plainTextToken;

    $task = Task::factory()->create();
    $user->tasks()->save($task);

    Storage::fake('avatars');

    // adding files that was not on accepted types
    $files = [
        UploadedFile::fake()->create('sample.mp3', '1000', 'mp3'),
        UploadedFile::fake()->create('sample.pdf', '1000', 'pdf'),
    ];
    
    $response = $this->withHeaders([
        'Authorization' => "Bearer {$token}"
    ])
    ->patchJson("/api/tasks/{$task->id}/attachments", [
        'attachments' => $files
    ]);
    
    $response
        ->assertStatus(422)
        ->assertJson([
            "message" => "The attachments.0 field must be a file of type: svg, png, jpg, mp4, csv, txt, doc, docx. (and 1 more error)",
            "errors" => [
                "attachments.0" => [
                    "The attachments.0 field must be a file of type: svg, png, jpg, mp4, csv, txt, doc, docx."
                ],
                "attachments.1" => [
                    "The attachments.1 field must be a file of type: svg, png, jpg, mp4, csv, txt, doc, docx."
                ]
            ]
        ]);
});

test('uploading files to a non-existent task receive 404 response', function() {
    $user = User::factory()->create();
    $token = $user->createToken($user->email)->plainTextToken;

    Storage::fake('avatars');

    $files = [
        UploadedFile::fake()->create(fake()->word . '.doc', 100)
    ];
    
    $response = $this->withHeaders([
        'Authorization' => "Bearer {$token}"
    ])
    ->patchJson("/api/tasks/999/attachments", [
        'attachments' => $files
    ]);

    $response->assertStatus(404);
});

test('uploading files to a task I do not own receive 401 response', function() {
    $userOwnerOfTask = User::factory()->create();
    $otherUser = User::factory()->create();

    $task = Task::factory()->create();
    $userOwnerOfTask->tasks()->save($task);

    $otherUserToken = $otherUser->createToken($otherUser->email)->plainTextToken;

    Storage::fake('avatars');

    $files = [
        UploadedFile::fake()->create(fake()->word . '.doc', 100)
    ];
    
    $response = $this->withHeaders([
        'Authorization' => "Bearer {$otherUserToken}"
    ])
    ->patchJson("/api/tasks/{$task->id}/attachments", [
        'attachments' => $files
    ]);

    $response->assertStatus(401);
});