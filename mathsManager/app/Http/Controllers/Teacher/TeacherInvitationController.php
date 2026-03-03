<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\ConfigureInvitationRequest;
use App\Models\StudentGroup;
use App\Models\TeacherInvitation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class TeacherInvitationController extends Controller
{
    /**
     * Créer ou régénérer le lien d'invitation du professeur.
     */
    public function configure(ConfigureInvitationRequest $request)
    {
        $teacher = Auth::user();
        $validated = $request->validated();

        // Vérifier que le groupe appartient à ce prof
        if ($validated['group_id'] ?? null) {
            $group = StudentGroup::findOrFail($validated['group_id']);
            abort_unless($group->teacher_id === $teacher->id, 403);
        }

        // Désactiver l'ancien lien
        TeacherInvitation::where('teacher_id', $teacher->id)
            ->where('is_active', true)
            ->update(['is_active' => false]);

        // Créer le nouveau
        TeacherInvitation::create([
            'teacher_id'   => $teacher->id,
            'group_id'     => $validated['group_id'] ?? null,
            'code'         => Str::upper(Str::random(8)),
            'max_uses'     => $validated['max_uses'],
            'current_uses' => 0,
            'is_active'    => true,
            'expires_at'   => now()->addYear(),
        ]);

        return back()->with('success', 'Lien d\'invitation généré.');
    }

    /**
     * Page publique de preview avant de rejoindre.
     */
    public function join(string $code): Response
    {
        $invitation = TeacherInvitation::with('teacher')
            ->where('code', $code)
            ->where('is_active', true)
            ->first();

        $isValid = $invitation && $invitation->isValid();
        $teacher = $invitation?->teacher;

        $alreadyJoined = false;
        $hasOtherTeacher = false;
        $isStaff = false;
        if (auth()->check()) {
            $currentUser = auth()->user();
            if ($currentUser->canActAsTeacher()) {
                $isStaff = true;
            } elseif ($isValid && $currentUser->teacher_id === $teacher?->id) {
                $alreadyJoined = true;
            } elseif ($currentUser->teacher_id !== null) {
                $hasOtherTeacher = true;
            }
        }

        return Inertia::render('Invitation/Join', [
            'invitation'      => $invitation,
            'teacher'         => $isValid ? $teacher : null,
            'isValid'         => $isValid,
            'alreadyJoined'   => $alreadyJoined,
            'hasOtherTeacher' => $hasOtherTeacher,
            'isStaff'         => $isStaff,
            'code'            => $code,
        ]);
    }

    /**
     * L'élève accepte l'invitation (auth requis).
     */
    public function accept(string $code)
    {
        $invitation = TeacherInvitation::with('teacher')
            ->where('code', $code)
            ->where('is_active', true)
            ->firstOrFail();

        abort_unless($invitation->isValid(), 422, 'Ce lien d\'invitation a expiré ou atteint sa limite.');

        /** @var User $student */
        $student = Auth::user();

        if ($student->canActAsTeacher()) {
            return redirect()
                ->route('invitation.join', ['code' => $code])
                ->with('error', 'Les professeurs et administrateurs ne peuvent pas rejoindre une classe en tant qu\'élève.');
        }

        $student->update([
            'teacher_id' => $invitation->teacher_id,
            'group_id'   => $invitation->group_id,
        ]);

        $invitation->incrementUses();

        return redirect()
            ->route('home')
            ->with('success', 'Vous avez rejoint la classe de ' . $invitation->teacher->name . ' !');
    }
}
