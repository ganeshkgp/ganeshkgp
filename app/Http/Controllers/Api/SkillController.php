<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class SkillController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $skills = Skill::active()->ordered()->get();

        return response()->json([
            'success' => true,
            'data' => $skills
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'experience_level' => 'required|string|max:255',
            'icon' => 'required|string|max:100',
            'color' => 'nullable|string|max:7',
            'position' => 'nullable|array',
            'position.x' => 'numeric',
            'position.y' => 'numeric',
            'position.z' => 'numeric',
            'proficiency' => 'numeric|min:0|max:1',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $skill = Skill::create($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Skill created successfully',
            'data' => $skill
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Skill $skill): JsonResponse
    {
        if (!$skill->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Skill not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $skill
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Skill $skill): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'experience_level' => 'sometimes|required|string|max:255',
            'icon' => 'sometimes|required|string|max:100',
            'color' => 'sometimes|nullable|string|max:7',
            'position' => 'sometimes|nullable|array',
            'position.x' => 'numeric',
            'position.y' => 'numeric',
            'position.z' => 'numeric',
            'proficiency' => 'sometimes|numeric|min:0|max:1',
            'sort_order' => 'sometimes|integer|min:0',
            'is_active' => 'sometimes|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $skill->update($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Skill updated successfully',
            'data' => $skill
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Skill $skill): JsonResponse
    {
        $skill->delete();

        return response()->json([
            'success' => true,
            'message' => 'Skill deleted successfully'
        ]);
    }
}
