<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Quizze;
use App\Models\QuizzDetail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;

class ProfileController extends Controller
{
    protected \App\Services\FileUploadService $fileUploadService;

    public function __construct(\App\Services\FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }

    /**
     * Get profile statistics based on user role.
     */
    private function getProfileStatistics(User $user): array
    {
        $statistics = [];

        if ($user->isStudent()) {
            $teacher = $user->teacher;
            $statistics['teacher_name'] = $teacher ? $teacher->name : 'Aucun';
            $statistics['teacher_avatar'] = $teacher ? $teacher->avatar : null;
            $statistics['teacher_role'] = $teacher ? $teacher->role : null;
        } elseif ($user->canActAsTeacher()) {
            $statistics['students_count'] = $user->students()->count();
            // Placeholder until relationships are clearer
            $statistics['corrections_count'] = 0; 
        }

        return $statistics;
    }

    /**
     * Display the user's profile view.
     */
    public function show(Request $request): \Inertia\Response
    {
        $user = $request->user();
        $statistics = $this->getProfileStatistics($user);

        return \Inertia\Inertia::render('Profile/Show', [
            'statistics' => $statistics,
        ]);
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): \Inertia\Response
    {
        $user = $request->user();
        $statistics = $this->getProfileStatistics($user);

        return \Inertia\Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail,
            'status' => session('status'),
            'statistics' => $statistics,
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }
        
        if ($request->input('remove_avatar') === 'true') {
            if ($user->avatar && $user->avatar != 'default.jpg' && !str_starts_with($user->avatar, 'http')) {
                $this->fileUploadService->delete('images/' . $user->avatar, true);
            }
            $user->avatar = 'default.jpg';
        } elseif ($request->hasFile('avatar')) {
            if ($user->avatar && $user->avatar != 'default.jpg' && !str_starts_with($user->avatar, 'http')) {
                $this->fileUploadService->delete('images/' . $user->avatar, true);
            }
    
            $avatarPath = $this->fileUploadService->upload(
                file: $request->file('avatar'),
                context: 'images',
                identifier: '',
                type: 'image',
                isPublic: true,
                customName: str_replace(['@', '.'], '-', $user->email) . '-' . time()
            );
            $user->avatar = basename($avatarPath);
        }
    
        $user->save();
        
        return Redirect::route('profile.edit')->with('success', 'Profil mis à jour avec succès.');
    }


    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'confirmation' => ['required', 'string', 'regex:/supprimer mon compte/'],
        ]);

        $user = $request->user();

        if ($user->avatar !== 'default.jpg') {
            $this->fileUploadService->delete('images/' . $user->avatar, true);
        }

        Auth::logout();

        // Supprimer les quizzes et leurs détails avant suppression (contraintes FK)
        $quizIds = Quizze::where('student_id', $user->id)->pluck('id');
        QuizzDetail::whereIn('quizz_id', $quizIds)->delete();
        Quizze::where('student_id', $user->id)->delete();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

}
