<?php

namespace App\Http\Controllers\Onboarding;

use App\Http\Controllers\Controller;
use App\Models\TeacherApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OnboardingController extends Controller
{
    /**
     * Page de sélection du rôle (première connexion).
     */
    public function role(): Response
    {
        return Inertia::render('Onboarding/RoleSelection');
    }

    /**
     * Assign le rôle étudiant à l'utilisateur.
     */
    public function chooseStudent(Request $request): RedirectResponse
    {
        $user = $request->user();

        $user->role = 'student';
        $user->status = 'active';
        $user->save();

        return redirect()->route('home');
    }

    /**
     * Démarre le flow d'inscription prof → formulaire de profil.
     */
    public function chooseTeacher(Request $request): Response
    {
        return Inertia::render('Onboarding/TeacherForm');
    }

    /**
     * Soumet le formulaire de candidature professeur.
     */
    public function submitTeacherForm(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'bio'            => ['required', 'string', 'max:1000'],
            'location'       => ['required', 'string', 'max:255'],
            'teaching_level' => ['required', 'in:college,lycee,prepa,superieur,autre'],
            'diploma'        => ['required', 'in:licence,master,agregation,capes,doctorat,autre'],
            'phone'          => ['nullable', 'string', 'max:20'],
        ]);

        $user = $request->user();

        // Mettre à jour le profil
        $user->fill($validated);
        $user->role = 'teacher';
        $user->status = 'pending_approval';
        $user->save();

        // Créer ou mettre à jour la candidature
        TeacherApplication::updateOrCreate(
            ['user_id' => $user->id],
            ['status' => 'pending']
        );

        // TODO: notifier l'admin par email

        return redirect()->route('onboarding.pending');
    }

    /**
     * Page d'attente de validation (prof en pending_approval).
     */
    public function pending(Request $request): Response|RedirectResponse
    {
        $user = $request->user();

        if ($user->status !== 'pending_approval') {
            return redirect()->route('home');
        }

        $application = $user->teacherApplication;

        return Inertia::render('Onboarding/PendingApproval', [
            'applicationDate' => $application ? $application->created_at->format('d/m/Y') : null,
        ]);
    }

    /**
     * Page de rejet (candidature prof refusée).
     */
    public function rejected(Request $request): Response|RedirectResponse
    {
        $user = $request->user();

        if ($user->status !== 'rejected') {
            return redirect()->route('home');
        }

        $application = $user->teacherApplication;

        return Inertia::render('Onboarding/Rejected', [
            'adminNotes' => $application?->admin_notes,
            'applicationDate' => $application ? $application->created_at->format('d/m/Y') : null,
        ]);
    }
    /**
     * Professeur rejeté qui choisit de devenir élève à la place.
     */
    public function switchToStudent(Request $request): RedirectResponse
    {
        $user = $request->user();

        $user->role = 'student';
        $user->status = 'active';
        $user->save();

        return redirect()->route('home');
    }
}
