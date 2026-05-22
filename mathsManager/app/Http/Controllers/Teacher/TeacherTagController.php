<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\TeacherTag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherTagController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', TeacherTag::class);

        $validated = $request->validate([
            'name'  => 'required|string|max:50',
            'color' => 'nullable|string|max:100',
        ]);

        $tag = Auth::user()->teacherTags()->firstOrCreate(
            ['name' => $validated['name']],
            ['color' => $validated['color'] ?? null]
        );

        return response()->json($tag);
    }

    public function update(TeacherTag $tag, Request $request): JsonResponse
    {
        $this->authorize('update', $tag);

        $validated = $request->validate([
            'color' => 'nullable|string|max:100',
        ]);

        $tag->update(['color' => $validated['color'] ?? null]);

        return response()->json($tag);
    }

    public function destroy(TeacherTag $tag): JsonResponse
    {
        $this->authorize('delete', $tag);

        $tag->delete();

        return response()->json(null, 204);
    }
}
