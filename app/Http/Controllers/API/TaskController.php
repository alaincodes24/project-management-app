<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $user = $request->user();
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthenticated.',
                ], 401);
            }

            $tasks = $user->tasks()
                ->when($request->status, fn($q) => $q->where('status', $request->status))
                ->when($request->priority, fn($q) => $q->where('priority', $request->priority))
                ->latest()
                ->get();

            return response()->json([
                'status' => 'success',
                'tasks' => TaskResource::collection($tasks),
            ]);
        } catch (Throwable $e) {
            Log::error('Fetching tasks failed: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch tasks.',
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        try {
            $user = $request->user();
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthenticated.',
                ], 401);
            }

            $task = $user->tasks()->create($request->validated());

            return response()->json([
                'status' => 'success',
                'message' => 'Task created successfully',
                'task' => new TaskResource($task),
            ], 201);
        } catch (Throwable $e) {
            Log::error('Task creation failed: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create task.',
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Task $task)
    {
        try {
            if ($task->user_id !== $request->user()?->id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized to view this task.',
                ], 403);
            }

            return response()->json([
                'status' => 'success',
                'task' => new TaskResource($task),
            ]);
        } catch (Throwable $e) {
            Log::error('Task fetch failed: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch task.',
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        try {
            if ($task->user_id !== $request->user()?->id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized to update this task.',
                ], 403);
            }

            $task->update($request->validated());

            return response()->json([
                'status' => 'success',
                'message' => 'Task updated',
                'task' => new TaskResource($task),
            ]);
        } catch (Throwable $e) {
            Log::error('Task update failed: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update task.',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Task $task)
    {
        try {
            if ($task->user_id !== $request->user()?->id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized to delete this task.',
                ], 403);
            }

            $task->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Task deleted',
            ]);
        } catch (Throwable $e) {
            Log::error('Task deletion failed: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete task.',
            ], 500);
        }
    }
}
