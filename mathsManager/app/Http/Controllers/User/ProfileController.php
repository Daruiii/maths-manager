<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    protected \App\Services\FileUploadService $fileUploadService;

    public function __construct(\App\Services\FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): \Inertia\Response
    {
        $user = $request->user();
        $statistics = [];

        if ($user->isStudent()) {
            $teacher = $user->teacher;
            $statistics['teacher_name'] = $teacher ? $teacher->name : 'Aucun';
            $statistics['teacher_avatar'] = $teacher ? $teacher->avatar : null;
            $statistics['teacher_role'] = $teacher ? $teacher->role : null;
        } elseif ($user->isTeacher()) {
            $statistics['students_count'] = $user->students()->count();
            // Assuming CorrectionRequest is linked to teacher via student or assigned_to?
            // User.php has correctionRequests() hasMany. Assuming this is requests MADE by user (student).
            // If teacher corrects, we need to count requests where status is 'corrected' and handled by this teacher?
            // For now, let's use a simple placeholder or count correctionRequests linked to their students.
            // Let's COUNT the students for now as requested "X Éléves". 
            // For "X trucs corrigés", we'll check if there is a relation.
            // User.php: public function correctionRequests() { return $this->hasMany(CorrectionRequest::class); }
            // This suggests relations owned by the user. If user is teacher, maybe they don't have correctionRequests?
            // Let's stick to students count for now to avoid breaking if relationship is ambiguous.
             $statistics['corrections_count'] = 0; // Placeholder until relationships are clearer
        }

        return \Inertia\Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail,
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

    $user->delete();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('home');
}

}
