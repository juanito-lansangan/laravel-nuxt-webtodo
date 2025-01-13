<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttachmentRequest;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\TagRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Tag;
use App\Models\Task;
use App\Services\FileAttachmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     * @response AnonymousResourceCollection<LengthAwarePaginator<TaskResource>>
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $search = $request->search;

        $data = $request->user()->tasks()
        ->filter($request, $search)
        ->sort($request)
        ->paginate(6)
        ->withQueryString();

        $debug = $request->user()->tasks()
        ->filter($request, $search)
        ->sort($request)
        // ->paginate(6)
        ->toSql();

        Log::info($request->all());
        Log::info($debug);

        return TaskResource::collection($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request, FileAttachmentService $fileAttachmentService): TaskResource
    {
        $fields = $request->validated();
        
        $task = $request->user()->tasks()->create($fields);

        if ($request->tags) {
            $tags = Tag::whereIn('name', $request->tags)->get();
            $task->tags()->sync($tags);
        }

        if ($request->attachments) {
            $fileAttachmentService->upload($request->attachments, $task);
        }

        return TaskResource::make($task);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task): TaskResource
    {
        Gate::authorize('viewOrModify', $task);

        return TaskResource::make($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task): TaskResource
    {
        Gate::authorize('viewOrModify', $task);

        $fields = $request->validated();
        
        $task->update($fields);

        if ($request->tags) {
            $tags = Tag::whereIn('name', $request->tags)->get();
            $task->tags()->sync($tags);
        }

        return TaskResource::make($task);
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
    public function complete(Task $task): TaskResource
    {
        Gate::authorize('viewOrModify', $task);

        $task->update([
            'completed_at' => now()
        ]);

        return TaskResource::make($task);
    }

    /**
     * Mark a task as inprogress
     */
    public function inprogress(Task $task): TaskResource
    {
        Gate::authorize('viewOrModify', $task);

        $task->completed_at = null;
        $task->save();

        return TaskResource::make($task);
    }

    /**
     * Archive a task
     */
    public function archive(Task $task): TaskResource
    {
        Gate::authorize('viewOrModify', $task);

        $task->update([
            'archived_at' => now()
        ]);

        return TaskResource::make($task);
    }

    /**
     * Restore an archive task
     */
    public function restore(Task $task): TaskResource
    {
        Gate::authorize('viewOrModify', $task);

        $task->archived_at = null;
        $task->save();

        return TaskResource::make($task);
    }

    /**
     * Adding tags to a task
     */
    public function addTags(Task $task, TagRequest $request): TaskResource
    {
        Gate::authorize('viewOrModify', $task);

        $tags = Tag::whereIn('name', $request->tags)->get();

        $task->tags()->sync($tags);
        
        return TaskResource::make($task);
    }

    /**
     * Adding attachments to a task
     *
     */
    public function addAttachments(Task $task, AttachmentRequest $request, FileAttachmentService $fileAttachmentService): TaskResource
    {
        Gate::authorize('viewOrModify', $task);
        
        $fileAttachmentService->upload($request->attachments, $task);

        return TaskResource::make($task);
    }
    
}
