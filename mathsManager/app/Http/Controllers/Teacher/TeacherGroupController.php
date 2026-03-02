<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\StoreStudentGroupRequest;
use App\Http\Requests\Teacher\UpdateStudentGroupRequest;
use App\Models\StudentGroup;
use Illuminate\Support\Facades\Auth;

class TeacherGroupController extends Controller
{
    /**
     * Créer un nouveau groupe.
     */
    public function store(StoreStudentGroupRequest $request)
    {
        $this->authorize('create', StudentGroup::class);

        StudentGroup::create([
            'teacher_id' => Auth::id(),
            'name'       => $request->validated('name'),
        ]);

        return back()->with('success', 'Groupe créé avec succès.');
    }

    /**
     * Renommer un groupe.
     */
    public function update(UpdateStudentGroupRequest $request, StudentGroup $group)
    {
        $this->authorize('update', $group);

        $group->update(['name' => $request->validated('name')]);

        return back()->with('success', 'Groupe renommé.');
    }

    /**
     * Supprimer un groupe (les élèves deviennent "sans groupe").
     */
    public function destroy(StudentGroup $group)
    {
        $this->authorize('delete', $group);

        // Détacher les élèves du groupe sans les supprimer
        $group->students()->update(['group_id' => null]);

        $group->delete();

        return back()->with('success', 'Groupe supprimé. Les élèves ont été déplacés dans "Sans groupe".');
    }
}
