<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $projects = Project::active()->ordered()->get();

        return response()->json([
            'success' => true,
            'data' => $projects
        ]);
    }

    /**
     * Display featured projects.
     */
    public function featured(): JsonResponse
    {
        $projects = Project::active()->featured()->ordered()->limit(6)->get();

        return response()->json([
            'success' => true,
            'data' => $projects
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'technologies' => 'required|array',
            'technologies.*' => 'string|max:100',
            'live_url' => 'nullable|url|max:255',
            'github_url' => 'nullable|url|max:255',
            'demo_url' => 'nullable|url|max:255',
            'position' => 'nullable|array',
            'position.x' => 'numeric',
            'position.y' => 'numeric',
            'position.z' => 'numeric',
            'color' => 'nullable|string|max:7',
            'featured' => 'boolean',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $project = Project::create($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Project created successfully',
            'data' => $project
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project): JsonResponse
    {
        if (!$project->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Project not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $project
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'technologies' => 'sometimes|required|array',
            'technologies.*' => 'string|max:100',
            'live_url' => 'sometimes|nullable|url|max:255',
            'github_url' => 'sometimes|nullable|url|max:255',
            'demo_url' => 'sometimes|nullable|url|max:255',
            'position' => 'sometimes|nullable|array',
            'position.x' => 'numeric',
            'position.y' => 'numeric',
            'position.z' => 'numeric',
            'color' => 'sometimes|nullable|string|max:7',
            'featured' => 'sometimes|boolean',
            'sort_order' => 'sometimes|integer|min:0',
            'is_active' => 'sometimes|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $project->update($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Project updated successfully',
            'data' => $project
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project): JsonResponse
    {
        // Delete associated image if exists
        if ($project->image && Storage::disk('public')->exists('projects/' . $project->image)) {
            Storage::disk('public')->delete('projects/' . $project->image);
        }

        $project->delete();

        return response()->json([
            'success' => true,
            'message' => 'Project deleted successfully'
        ]);
    }
}
