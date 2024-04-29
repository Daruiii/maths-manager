<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    // Affiche la liste des utilisateurs
    public function index(Request $request)
    {
        $search = $request->get('search');
        if ($search) {
            $users = User::where('name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%')
                ->orWhere('role', 'like', '%' . $search . '%')
                ->get();
        } else {
            $users = User::all();
        }

        return view('user.index', compact('users'));
    }


    // Affiche le formulaire de création d'un nouvel utilisateur
    public function create()
    {
        return view('user.create');
    }

    // Enregistre un nouvel utilisateur dans la base de données
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'role' => 'required|in:admin,student,teacher',
            'password' => 'required|min:6',
        ]);

        $user = User::create($validatedData);
        return redirect()->route('users.index');
    }

    // Affiche le formulaire pour modifier un utilisateur existant
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = ['admin', 'student', 'teacher'];
        return view('user.edit', compact('user', 'roles'));
    }

    // Met à jour un utilisateur dans la base de données
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:admin,student,teacher',
            'password' => 'sometimes|min:6',
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ]);
    
        $user = User::findOrFail($id);
    
        if ($request->hasFile('avatar')) {
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
            $avatarName = $user->email . '-' . $newAvatar->getClientOriginalName();
            $newAvatar->move($destinationPath, $avatarName);
            $user->avatar = basename($avatarName);
        }
    
        $user->update($validatedData);
        return redirect()->route('users.index');
    }
    

    // Supprime un utilisateur de la base de données par un admin
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->avatar && $user->avatar != 'default.jpg') {
            $avatarPath = public_path('/storage/images/' . $user->avatar);
            if (file_exists($avatarPath)) {
                unlink($avatarPath);
            }
        }

        $user->delete();

        return redirect()->route('users.index');
    }

    public function verify($id)
    {
        $user = User::findOrFail($id);
        $user->verified = true;
        $user->save();

        return redirect()->route('users.index');
    }

    public function unverify($id)
    {
        $user = User::findOrFail($id);
        $user->verified = false;
        $user->save();

        return redirect()->route('users.index');
    }

    public function resetLastDSGeneratedAt($id)
    {
        $user = User::findOrFail($id);
        $user->last_ds_generated_at = null;
        $user->save();

        return redirect()->route('users.index');
    }
}
