<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exercise;
use App\Models\User;
use App\Models\ExerciseWhitelist;
use Illuminate\Support\Facades\Auth;

class ExerciseWhitelistController extends Controller
{
    /**
     * Afficher la page de gestion de whitelist pour un exercice
     */
    public function show($exerciseId)
    {
        $exercise = Exercise::with(['whitelistedUsers', 'subchapter.chapter.classe'])->findOrFail($exerciseId);
        $students = User::where('role', 'student')->orderBy('name')->get();
        
        return view('exercise.whitelist', compact('exercise', 'students'));
    }
    
    /**
     * Ajouter un étudiant à la whitelist d'un exercice
     */
    public function addStudent(Request $request, $exerciseId)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);
        
        $exercise = Exercise::findOrFail($exerciseId);
        
        // Vérifier que l'utilisateur est un étudiant
        $user = User::findOrFail($request->user_id);
        if ($user->role !== 'student') {
            return redirect()->back()->with('error', 'Seuls les étudiants peuvent être ajoutés à la whitelist.');
        }
        
        // Ajouter à la whitelist (unique constraint gère les doublons)
        try {
            ExerciseWhitelist::create([
                'exercise_id' => $exerciseId,
                'user_id' => $request->user_id
            ]);
            
            return redirect()->back()->with('success', $user->name . ' a été ajouté(e) à la whitelist.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Cet étudiant est déjà dans la whitelist.');
        }
    }
    
    /**
     * Retirer un étudiant de la whitelist d'un exercice
     */
    public function removeStudent($exerciseId, $userId)
    {
        $whitelistEntry = ExerciseWhitelist::where('exercise_id', $exerciseId)
                                          ->where('user_id', $userId)
                                          ->first();
        
        if ($whitelistEntry) {
            $user = User::find($userId);
            $whitelistEntry->delete();
            return redirect()->back()->with('success', $user->name . ' a été retiré(e) de la whitelist.');
        }
        
        return redirect()->back()->with('error', 'Étudiant non trouvé dans la whitelist.');
    }
}
