<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Attachment;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rules\File;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        // valid sort items
        $sortItems = [
            'title' => 'title',
            'description' => 'description',
            'created_at' => 'created at',
            'completed_at' => 'completed',
            'priority' => 'priority level',
            'due_date' => 'due date',
        ];

        // valid date filters
        $dateFilters = [
            'due_date',
            'completed_at',
            'archived_at',
            'created_at',
        ];

        $search = $request->search;

        $data = $request->user()->tasks()
        ->filter($request, $search, $dateFilters)
        ->sort($request, $sortItems)
        ->paginate(10);

        return response()->json($data);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request): JsonResponse
    {
        $fields = $request->validated();
        
        $task = $request->user()->tasks()->create($fields);

        if ($request->tags) {
            $task->tags()->sync($request->tags);
            $task->load('tags');
        }

        if ($request->attachments) {
            $uploadFiles = [];

            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments');

                $uploadFiles[] = new Attachment([
                    'path' => $path,
                    'type' => $file->getClientOriginalExtension()
                ]);
            }

            $task->attachments()->saveMany($uploadFiles);
            $task->load('attachments');
        }

        return response()->json($task, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task): JsonResponse
    {
        Gate::authorize('viewOrModify', $task);

        return response()->json($task, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task): JsonResponse
    {
        Gate::authorize('viewOrModify', $task);

        $fields = $request->validated();
        
        $task->update($fields);

        if ($request->tags) {
            $task->tags()->sync($request->tags);
            $task->load('tags');
        }

        return response()->json($task, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task): JsonResponse
    {
        Gate::authorize('viewOrModify', $task);

        $task->delete();

        return response()->json([], 204);
    }

    /**
     * Mark a task as complete
     */
    public function complete(Task $task): JsonResponse
    {
        Gate::authorize('viewOrModify', $task);

        $task->update([
            'completed_at' => now()
        ]);

        return response()->json($task, 200);
    }

    /**
     * Mark a task as incomplete
     */
    public function incomplete(Task $task): JsonResponse
    {
        Gate::authorize('viewOrModify', $task);

        $task->completed_at = null;
        $task->save();

        return response()->json($task, 200);
    }

    /**
     * Archive a task
     */
    public function archive(Task $task): JsonResponse
    {
        Gate::authorize('viewOrModify', $task);

        $task->update([
            'archived_at' => now()
        ]);

        return response()->json($task, 200);
    }

    /**
     * Restore an archive task
     */
    public function restore(Task $task): JsonResponse
    {
        Gate::authorize('viewOrModify', $task);

        $task->archived_at = null;
        $task->save();

        return response()->json($task, 200);
    }

    /**
     * Adding tags to a task
     */
    public function addTags(Task $task, Request $request): JsonResponse
    {
        $request->validate([
            'tags' => ['required', 'array'],
            'tags.*' => ['integer', 'exists:tags,id']
        ]);

        Gate::authorize('viewOrModify', $task);

        $task->tags()->sync($request->tags);

        $task->load('tags');
        
        return response()->json($task, 200);
    }

    /**
     * Adding attachments to a task
     *
     */
    public function addAttachments(Task $task, Request $request): JsonResponse
    {
        $request->validate([
            'attachments' => ['required', 'array'],
            'attachments.*' => ['file', 'mimes:svg,png,jpg,mp4,csv,txt,doc,docx', 'max:10240']
        ]);

        Gate::authorize('viewOrModify', $task);
        
        $uploadFiles = [];

        foreach ($request->file('attachments') as $file) {
            $path = $file->store('attachments');

            $uploadFiles[] = new Attachment([
                'path' => $path,
                'type' => $file->getClientOriginalExtension()
            ]);
        }

        $task->attachments()->saveMany($uploadFiles);

        $task->load('attachments');

        return response()->json($task, 200);
    }
    
}
