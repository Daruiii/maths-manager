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
        ]);

        $user = User::findOrFail($id);
        $user->update($validatedData);
        return redirect()->route('users.index');
    }

    // Supprime un utilisateur de la base de données
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('users.index');
    }
}
