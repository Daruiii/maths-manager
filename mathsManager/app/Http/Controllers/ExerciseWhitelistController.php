<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exercise;
use App\Models\User;
use App\Models\ExerciseWhitelist;
use App\Models\WhitelistRequest;
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
        
        // Récupérer les demandes en attente pour cet exercice
        $pendingRequests = WhitelistRequest::with('user')
            ->where('exercise_id', $exerciseId)
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('exercise.whitelist', compact('exercise', 'students', 'pendingRequests'));
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
            
            // Vérifier s'il y a une demande en attente pour cet étudiant et cet exercice
            $pendingRequest = WhitelistRequest::where('exercise_id', $exerciseId)
                                             ->where('user_id', $request->user_id)
                                             ->where('status', 'pending')
                                             ->first();
            
            if ($pendingRequest) {
                // Marquer la demande comme approuvée
                $pendingRequest->update([
                    'status' => 'approved',
                    'processed_at' => now(),
                    'admin_response' => 'Accès accordé manuellement par l\'administrateur.'
                ]);
                
                return redirect()->back()->with('success', $user->name . ' a été ajouté(e) à la whitelist et sa demande a été approuvée.');
            }
            
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
            
            // Supprimer l'entrée de la whitelist
            $whitelistEntry->delete();
            
            // IMPORTANT: Supprimer aussi la demande de whitelist approuvée correspondante
            // pour permettre à l'étudiant de redemander l'accès plus tard
            WhitelistRequest::where('exercise_id', $exerciseId)
                           ->where('user_id', $userId)
                           ->where('status', 'approved')
                           ->delete();
            
            return redirect()->back()->with('success', $user->name . ' a été retiré(e) de la whitelist. L\'étudiant peut maintenant redemander l\'accès si nécessaire.');
        }
        
        return redirect()->back()->with('error', 'Étudiant non trouvé dans la whitelist.');
    }
}
