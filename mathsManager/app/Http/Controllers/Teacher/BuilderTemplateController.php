<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\StoreBuilderTemplateRequest;
use App\Http\Requests\Teacher\UpdateBuilderTemplateRequest;
use App\Models\BuilderTemplate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class BuilderTemplateController extends Controller
{
    public function store(StoreBuilderTemplateRequest $request): RedirectResponse
    {
        $data = $request->validated();

        BuilderTemplate::create([
            'teacher_id'       => Auth::id(),
            'type'             => $data['type'],
            'name'             => $data['name'],
            'student_group_id' => $data['student_group_id'],
            'payload'          => $data['payload'],
        ]);

        return back()->with('success', 'Modèle "' . $data['name'] . '" sauvegardé.');
    }

    public function update(UpdateBuilderTemplateRequest $request, BuilderTemplate $template): RedirectResponse
    {
        if ($template->teacher_id !== Auth::id()) {
            abort(403);
        }

        $data = $request->validated();

        $template->name             = $data['name'];
        $template->student_group_id = $data['student_group_id'];

        if (array_key_exists('payload', $data)) {
            $template->payload = $data['payload'];
        }

        $template->save();

        return back()->with('success', 'Modèle "' . $template->name . '" mis à jour.');
    }

    public function destroy(BuilderTemplate $template): RedirectResponse
    {
        if ($template->teacher_id !== Auth::id()) {
            abort(403);
        }

        $name = $template->name;
        $template->delete();

        return back()->with('success', 'Modèle "' . $name . '" supprimé.');
    }
}
