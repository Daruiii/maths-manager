<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WhitelistRequest;
use App\Models\Exercise;
use App\Models\ExerciseWhitelist;
use Illuminate\Support\Facades\Auth;

class WhitelistRequestController extends Controller
{
    /**
     * Soumettre une demande de whitelist pour un exercice
     */
    public function store(Request $request, $exerciseId)
    {
        $request->validate([
            'message' => 'nullable|string|max:1000'
        ]);
        
        $exercise = Exercise::findOrFail($exerciseId);
        
        // Vérifier que l'utilisateur est un étudiant
        if (Auth::user()->role !== 'student') {
            return redirect()->back()->with('error', 'Seuls les étudiants peuvent demander l\'accès aux corrections.');
        }
        
        // Vérifier qu'il n'est pas déjà whitelisé
        if ($exercise->isWhitelisted(Auth::id())) {
            return redirect()->back()->with('error', 'Vous avez déjà accès à la correction de cet exercice.');
        }
        
        // Vérifier qu'il n'a pas déjà fait une demande
        if ($exercise->hasWhitelistRequest(Auth::id())) {
            return redirect()->back()->with('error', 'Vous avez déjà soumis une demande pour cet exercice.');
        }
        
        // Créer la demande
        WhitelistRequest::create([
            'user_id' => Auth::id(),
            'exercise_id' => $exerciseId,
            'message' => $request->message,
            'status' => WhitelistRequest::STATUS_PENDING
        ]);
        
        return redirect()->back()->with('success', 'Votre demande d\'accès à la correction a été soumise avec succès.');
    }
    
    /**
     * Lister toutes les demandes en attente (pour les admins)
     */
    public function index()
    {
        $pendingRequests = WhitelistRequest::with(['user', 'exercise.subchapter.chapter.classe'])
            ->pending()
            ->orderBy('created_at', 'desc')
            ->get();
            
        $processedRequests = WhitelistRequest::with(['user', 'exercise.subchapter.chapter.classe', 'processedBy'])
            ->processed()
            ->orderBy('processed_at', 'desc')
            ->limit(50) // Limiter pour les performances
            ->get();
            
        return view('exercise.whitelist-requests', compact('pendingRequests', 'processedRequests'));
    }
    
    /**
     * Approuver une demande de whitelist
     */
    public function approve(Request $request, $requestId)
    {
        $whitelistRequest = WhitelistRequest::findOrFail($requestId);
        
        if (!$whitelistRequest->isPending()) {
            return redirect()->back()->with('error', 'Cette demande a déjà été traitée.');
        }
        
        // Ajouter à la whitelist
        try {
            ExerciseWhitelist::create([
                'exercise_id' => $whitelistRequest->exercise_id,
                'user_id' => $whitelistRequest->user_id
            ]);
            
            // Mettre à jour la demande
            $whitelistRequest->update([
                'status' => WhitelistRequest::STATUS_APPROVED,
                'admin_response' => $request->admin_response,
                'processed_by' => Auth::id(),
                'processed_at' => now()
            ]);
            
            return redirect()->back()->with('success', 'Demande approuvée et accès accordé.');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de l\'ajout à la whitelist.');
        }
    }
    
    /**
     * Rejeter une demande de whitelist
     */
    public function reject(Request $request, $requestId)
    {
        $request->validate([
            'admin_response' => 'required|string|max:500'
        ]);
        
        $whitelistRequest = WhitelistRequest::findOrFail($requestId);
        
        if (!$whitelistRequest->isPending()) {
            return redirect()->back()->with('error', 'Cette demande a déjà été traitée.');
        }
        
        $whitelistRequest->update([
            'status' => WhitelistRequest::STATUS_REJECTED,
            'admin_response' => $request->admin_response,
            'processed_by' => Auth::id(),
            'processed_at' => now()
        ]);
        
        return redirect()->back()->with('success', 'Demande rejetée.');
    }
    
    /**
     * Afficher les demandes de l'utilisateur connecté
     */
    public function myRequests()
    {
        $requests = WhitelistRequest::with(['exercise.subchapter.chapter.classe', 'processedBy'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('exercise.my-whitelist-requests', compact('requests'));
    }
    
    /**
     * Supprimer une demande spécifique
     */
    public function destroy($requestId)
    {
        $whitelistRequest = WhitelistRequest::findOrFail($requestId);
        
        // Si la demande était approuvée, retirer aussi l'étudiant de la whitelist
        if ($whitelistRequest->status === WhitelistRequest::STATUS_APPROVED) {
            ExerciseWhitelist::where('exercise_id', $whitelistRequest->exercise_id)
                            ->where('user_id', $whitelistRequest->user_id)
                            ->delete();
        }
        
        $whitelistRequest->delete();
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Supprimer toutes les demandes traitées (vider l'historique)
     */
    public function clearHistory()
    {
        // Récupérer les demandes approuvées avant de les supprimer
        $approvedRequests = WhitelistRequest::where('status', WhitelistRequest::STATUS_APPROVED)->get();
        
        // Retirer de la whitelist tous les étudiants dont les demandes approuvées vont être supprimées
        foreach ($approvedRequests as $request) {
            ExerciseWhitelist::where('exercise_id', $request->exercise_id)
                            ->where('user_id', $request->user_id)
                            ->delete();
        }
        
        // Supprimer toutes les demandes traitées
        WhitelistRequest::whereIn('status', ['approved', 'rejected'])->delete();
        
        return response()->json(['success' => true]);
    }
}
