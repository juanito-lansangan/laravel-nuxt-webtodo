<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $sortItems = [
            'created_at' => 'created at',
            'completed_date' => 'completed',
            'priority' => 'priority level',
            'due_date' => 'due date',
        ];

        $search = $request->search;

        $data = $request->user()->tasks()
        ->where(function($query) use ($search) {
            $query
                ->where('title', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        })
        ->when($request->priority_filter, function($query, $filter) {
            $query
                ->where('priority', $filter);
        })
        ->when($request->due_date_from && $request->due_date_to, function($query) use ($request) {
            $query
                ->whereDate('due_date', '>=', $request->due_date_from)
                ->whereDate('due_date', '<=', $request->due_date_to);

        })
        ->when($request->completed_date_from && $request->completed_date_to, function($query) use ($request) {
            $query
                ->whereDate('completed_date', '>=', $request->completed_date_from)
                ->whereDate('completed_date', '<=', $request->completed_date_to);

        })
        ->when($request->archived_date_from && $request->archived_date_to, function($query) use ($request) {
            $query
                ->whereDate('archived_date', '>=', $request->archived_date_from)
                ->whereDate('archived_date', '<=', $request->archived_date_to);

        })
        ->when($request->sort_by && in_array($request->sort_by, array_keys($sortItems)), function($query) use ($request) {
            $query
                ->orderBy($request->sort_by, $request->sort_order ?? 'ASC');
        })
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

        // $task->tags()->sync($fields->only(['tags']));

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

    public function complete(Task $task): JsonResponse
    {
        Gate::authorize('viewOrModify', $task);

        $task->update([
            'completed_at' => now()
        ]);

        return response()->json($task, 200);
    }
    
}
