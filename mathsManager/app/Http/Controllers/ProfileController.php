<?php

namespace App\Http\Controllers;

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
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

public function update(ProfileUpdateRequest $request): RedirectResponse
{
    $user = $request->user();
    $user->fill($request->validated());
    
    if ($request->input('remove_avatar') === 'true') {
        if ($user->avatar && $user->avatar != 'default.jpg') {
            $this->fileUploadService->delete('images/' . $user->avatar, true);
        }
        $user->avatar = 'default.jpg';
    } elseif ($request->hasFile('avatar')) {
        if ($user->avatar && $user->avatar != 'default.jpg') {
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
    
    return Redirect::route('profile.edit')->with('status', 'profile-updated');
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
