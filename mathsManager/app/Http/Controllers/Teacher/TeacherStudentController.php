<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\UpdateStudentGroupAssignmentRequest;
use App\Models\StudentGroup;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class TeacherStudentController extends Controller
{
    /**
     * Page principale : liste des dossiers (groupes) + élèves sans groupe.
     */
    public function index(): Response
    {
        $teacher = Auth::user();

        $this->authorize('viewAny', StudentGroup::class);

        $groups = StudentGroup::where('teacher_id', $teacher->id)
            ->withCount('students')
            ->with(['students' => fn($q) => $q->orderBy('first_name')])
            ->orderBy('name')
            ->get();

        $ungroupedStudents = User::where('teacher_id', $teacher->id)
            ->whereNull('group_id')
            ->orderBy('first_name')
            ->get();

        $invitation = $teacher->activeInvitation();

        return Inertia::render('Teacher/Students/Index', [
            'groups'            => $groups,
            'ungroupedStudents' => $ungroupedStudents,
            'invitation'        => $invitation,
        ]);
    }

    /**
     * Page de détail d'un groupe.
     */
    public function showGroup(StudentGroup $group): Response
    {
        $this->authorize('update', $group);

        $teacher = Auth::user();

        $students = User::where('group_id', $group->id)
            ->orderBy('first_name')
            ->get();

        $groups = StudentGroup::where('teacher_id', $teacher->id)
            ->withCount('students')
            ->orderBy('name')
            ->get();

        return Inertia::render('Teacher/Students/Group', [
            'group'    => $group,
            'students' => $students,
            'groups'   => $groups,
        ]);
    }

    /**
     * Désassocier un élève du professeur.
     */
    public function removeStudent(User $student)
    {
        $this->authorize('remove', $student);

        $student->update([
            'teacher_id' => null,
            'teacher_joined_at' => null,
            'group_id'   => null,
        ]);

        return back()->with('success', 'Élève désassocié avec succès.');
    }

    /**
     * Assigner ou changer le groupe d'un élève.
     */
    public function updateGroup(UpdateStudentGroupAssignmentRequest $request, User $student)
    {
        $this->authorize('updateGroup', $student);

        $groupId = $request->validated('group_id');

        // S'assurer que le groupe appartient bien au prof
        if ($groupId) {
            $group = StudentGroup::findOrFail($groupId);
            $this->authorize('update', $group);
        }

        $student->update(['group_id' => $groupId]);

        return back()->with('success', 'Groupe mis à jour.');
    }
}
