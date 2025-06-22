<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProjectController extends Controller
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

            $projects = $user->projects()->latest()->get();

            return response()->json([
                'status' => 'success',
                'projects' => ProjectResource::collection($projects),
            ]);
        } catch (Throwable $e) {
            Log::error('Fetching projects failed: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch projects.',
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthenticated.',
                ], 401);
            }

            $project = $user->projects()->create($request->validated());

            return response()->json([
                'status' => 'success',
                'message' => 'Project created successfully',
                'project' => new ProjectResource($project),
            ], 201);
        } catch (Throwable $e) {
            Log::error('Project creation failed: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create project.',
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Project $project)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthenticated.',
                ], 401);
            }

            if ($project->user_id !== $user->id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized to view this project.',
                ], 403);
            }

            return response()->json([
                'status' => 'success',
                'project' => new ProjectResource($project),
            ]);
        } catch (Throwable $e) {
            Log::error('Fetching project failed: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch project.',
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthenticated.',
                ], 401);
            }

            if ($project->user_id !== $user->id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized to update this project.',
                ], 403);
            }

            $project->update($request->validated());

            return response()->json([
                'status' => 'success',
                'message' => 'Project updated',
                'project' => new ProjectResource($project),
            ]);
        } catch (Throwable $e) {
            Log::error('Project update failed: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update project.',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Project $project)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthenticated.',
                ], 401);
            }

            if ($project->user_id !== $user->id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized to delete this project.',
                ], 403);
            }

            $project->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Project deleted',
            ]);
        } catch (Throwable $e) {
            Log::error('Project deletion failed: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete project.',
            ], 500);
        }
    }
}
