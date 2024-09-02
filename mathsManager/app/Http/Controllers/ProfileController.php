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
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

   /**
 * Update the user's profile information.
 */
public function update(ProfileUpdateRequest $request): RedirectResponse
{
    $user = $request->user();
    $user->fill($request->validated());
    
    // Vérifier si l'avatar doit être supprimé
    if ($request->input('remove_avatar') === 'true') {
        // Supprime l'ancien avatar si ce n'est pas l'avatar par défaut
        if ($user->avatar && $user->avatar != 'default.jpg') {
            $oldAvatarPath = public_path('/storage/images/' . $user->avatar);
            if (file_exists($oldAvatarPath)) {
                unlink($oldAvatarPath);
            }
        }
        $user->avatar = 'default.jpg';
    } elseif ($request->hasFile('avatar')) {
        // Supprime l'ancien avatar si ce n'est pas l'avatar par défaut
        if ($user->avatar && $user->avatar != 'default.jpg') {
            $oldAvatarPath = public_path('/storage/images/' . $user->avatar);
            if (file_exists($oldAvatarPath)) {
                unlink($oldAvatarPath);
            }
        }

        // Stocke le nouvel avatar
        $newAvatar = $request->file('avatar');
        $destinationPath = public_path('/storage/images');
        $avatarName = $user->email . '-' . time() . '.' . $newAvatar->getClientOriginalExtension();
        $newAvatar->move($destinationPath, $avatarName);
        $user->avatar = $avatarName;
    }

    $user->save();
    
    return Redirect::route('profile.edit')->with('status', 'profile-updated');
}


    /**
     * Delete the user's account.
     */
 /**
 * Delete the user's account.
 */
public function destroy(Request $request): RedirectResponse
{
    $request->validateWithBag('userDeletion', [
        // must write "supprimer mon compte"
        'confirmation' => ['required', 'string', 'regex:/supprimer mon compte/'],
    ]);

    $user = $request->user();

    $destinationPath = public_path('/storage/images');

    $avatarPath = $destinationPath.'/'.$user->avatar;

    if ($user->avatar !== 'default.jpg' && file_exists($avatarPath)) {
        unlink($avatarPath);
    }

    Auth::logout();

    $user->delete();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('home');
}

}
