<?php

use App\Enums\TaskPriority;
use App\Models\Tag;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('creating a task as an authenticated user with valid title, description, due date, and priority receive 200 response', function() {
    $user = User::factory()->create();
    $token = $user->createToken($user->email)->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => "Bearer {$token}",
    ])
    ->postJson('/api/tasks', [
        'title' => 'new task',
        'description' => 'test description',
        'due_date' => now()->addDays(5)->format('Y-m-d'),
        'priority' => TaskPriority::Urgent
    ]);

    $responseTask = $response->json();

    $response
        ->assertStatus(201)
        ->assertJson($responseTask);
});


test('creating a task as an authenticated user with tags receive 200 response', function() {
    $user = User::factory()->create();
    $token = $user->createToken($user->email)->plainTextToken;

    $tags = Tag::factory(3)->create();
    $tagsId = $tags->pluck('id')->toArray();

    $response = $this->withHeaders([
        'Authorization' => "Bearer {$token}",
    ])
    ->postJson('/api/tasks', [
        'title' => 'new task',
        'description' => 'test description',
        'due_date' => now()->addDays(5)->format('Y-m-d'),
        'priority' => TaskPriority::Urgent,
        'tags' => $tagsId
    ]);

    $responseTask = $response->json();
    expect($responseTask['data']['tags'])->toHaveCount(3);

    $response
        ->assertStatus(201)
        ->assertJson($responseTask);
});

test('creating a task as an authenticated user with invalid title and/or description receive 422 response', function() {

    $user = User::factory()->create();
    $token = $user->createToken($user->email)->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => "Bearer {$token}"
    ])
    ->postJson('/api/tasks', []);

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

test('creating a task with valid files receive 200 response with task data that includes attachments', function() {
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
        'Authorization' => "Bearer {$token}",
    ])
    ->postJson('/api/tasks', [
        'title' => 'new task',
        'description' => 'test description',
        'attachments' => $files
    ]);

    $responseTask = $response->json('data');
    expect($responseTask['attachments'])->toHaveCount(3);

    $response->assertStatus(201);
});

test('creating a task with invalid files receive 422 response with error message', function() {
    $user = User::factory()->create();
    $token = $user->createToken($user->email)->plainTextToken;

    $task = Task::factory()->create();
    $user->tasks()->save($task);

    Storage::fake('avatars');

    $files = [
        UploadedFile::fake()->create('sample.mp3', '1000', 'mp3'),
        UploadedFile::fake()->create('sample.pdf', '1000', 'pdf'),
    ];
    
    $response = $this->withHeaders([
        'Authorization' => "Bearer {$token}",
    ])
    ->postJson('/api/tasks', [
        'title' => 'new task',
        'description' => 'test description',
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