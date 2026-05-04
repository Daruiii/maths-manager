<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CorrectionRequest;
use App\Models\DS;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\CorrectionRequestMail;
use App\Mail\CorrectionCorrectedMail;
use App\Http\Requests\CorrectionRequest\SendCorrectionRequest;
use App\Http\Requests\CorrectionRequest\CorrectCorrectionRequest;
use App\Http\Requests\CorrectionRequest\UpdateCorrectionRequest;

class CorrectionRequestController extends Controller
{
    protected \App\Services\FileUploadService $fileUploadService;
    protected \App\Services\ImageManagementService $imageManagementService;

    public function __construct(
        \App\Services\FileUploadService $fileUploadService,
        \App\Services\ImageManagementService $imageManagementService
    ) {
        $this->fileUploadService = $fileUploadService;
        $this->imageManagementService = $imageManagementService;
    }

    // Méthode pour afficher toutes les demandes de correction (pour l'admin ou les professeurs)
    public function index()
    {
        $search = request()->query('search');
        if ($search) {
            $correctionRequests = CorrectionRequest::whereHas('ds', function ($query) use ($search) {
                $query->whereHas('user', function ($query) use ($search) {
                    $query->where('name', 'LIKE', "%{$search}%");
                });
            })->orderBy('status', 'asc')->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        } else {
            // status pending first
            $correctionRequests = CorrectionRequest::orderBy('status', 'asc')->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        }
        return view('correctionRequest.index', compact('correctionRequests'));
    }

    // Méthode to display the correction request form
    public function showCorrectionRequestForm($ds_id)
    {
        $ds = DS::where('id', $ds_id)->firstOrFail();
        return view('correctionRequest.requestForm', compact('ds'));
    }

    // Méthode to send a correction request
    public function sendCorrectionRequest(SendCorrectionRequest $request, $ds_id)
    {
        $ds = DS::where('id', $ds_id)->firstOrFail();
        if ($correctionRequest = CorrectionRequest::where('ds_id', $ds_id)->first()) {
            return redirect()->route('ds.myDS', Auth::id())->with('error', 'You have already sent a correction request for this DS');
        }
        $correctionRequest = new CorrectionRequest();
        $correctionRequest->user_id = Auth::user()->id;
        $correctionRequest->ds_id = $ds->id;
        $correctionRequest->status = 'pending';
        $correctionRequest->pictures = 'null'; // to avoid 'Array to string conversion' error
        $correctionRequest->grade = 0;
        $correctionRequest->save();

        // Supprimer l'ancien dossier de correction s'il existe
        $this->fileUploadService->deleteDirectory('corrections', 'ds-' . $ds_id, false);

        // Upload des photos élèves en PRIVÉ avec FileUploadService
        $uploadedPaths = $this->fileUploadService->uploadMultiple(
            files: $request->file('pictures'),
            context: 'corrections',
            identifier: 'ds-' . $ds_id,
            type: 'image',
            isPublic: false,  // PRIVÉ - accessible uniquement avec authentification
            prefix: 'student_'
        );

        $correctionRequest->pictures = $uploadedPaths;
        $correctionRequest->message = $request->message;
        $correctionRequest->save();

        // set ds status to 'sent'
        $ds->status = 'sent';
        $ds->save();

        // envoyer un mail à tous les admin et les professeurs 
        // $adminsTeachers = User::where('role', 'admin')
        // ->orWhere('role', 'teacher')
        // ->whereNotNull('email_verified_at')
        // ->get();
        // foreach ($adminsTeachers as $adminTeacher) {
        //     $mail = new CorrectionRequestMail($correctionRequest);
        //     Mail::to($adminTeacher->email)->send($mail);
        // }

        // envoyer un mail juste à max
        $mail = new CorrectionRequestMail($correctionRequest);
        Mail::to('maxime@mathsmanager.fr')->send($mail);

        return redirect()->route('ds.myDS', Auth::id())->with('success', 'Votre demande de correction a été envoyée avec succès');
    }

    // Méthode for show the correction request
    public function showCorrectionRequest($ds_id)
    {
        $ds = DS::where('id', $ds_id)->firstOrFail();
        $correctionRequest = CorrectionRequest::where('ds_id', $ds_id)->firstOrFail();
        $corrector = User::where('id', $correctionRequest->corrector_id)->first() ?? User::where('role', 'admin')->first();
        $pictures = $correctionRequest->pictures;
        $correctedPictures = $correctionRequest->correction_pictures;

        return view('correctionRequest.show', compact('ds', 'correctionRequest', 'pictures', 'correctedPictures', 'corrector'));
    }

    // Méthode pour formulaire de correction
    public function showCorrectionForm($ds_id)
    {
        $ds = DS::where('id', $ds_id)->firstOrFail();
        $correctionRequest = CorrectionRequest::where('ds_id', $ds_id)->firstOrFail();

        // Récupérer les images de correction existantes via ImageManagementService
        $existingCorrectionPictures = $this->imageManagementService->getFormattedImagesForComponent(
            context: 'corrections',
            identifier: 'ds-' . $ds_id,
            isPublic: false,
            pattern: 'corrected-*'
        );

        return view('correctionRequest.correctionForm', compact('ds', 'correctionRequest', 'existingCorrectionPictures'));
    }

    // Méthode pour qu'un professeur puisse corriger une demande de correction
    public function correctCorrectionRequest(CorrectCorrectionRequest $request, $ds_id)
    {
        $correctionRequest = CorrectionRequest::where('ds_id', $ds_id)->firstOrFail();

        $correctionRequest->status = 'corrected';
        $correctionRequest->grade = $request->grade;
        $correctionRequest->corrector_id = Auth::user()->id;
        $correctionRequest->correction_message = $request->correction_message;

        // Gestion des images de correction avec ImageManagementService
        $correctionImagePaths = $this->imageManagementService->handleImageUpload(
            request: $request,
            inputName: 'correction_pictures',
            deleteInputName: 'delete_correction_pictures',
            context: 'corrections',
            identifier: 'ds-' . $ds_id,
            prefix: 'corrected-',
            isPublic: false
        );

        $correctionRequest->correction_pictures = array_values($correctionImagePaths);

        $correctionRequest->save();

        // set ds status to 'finished'
        $ds = DS::where('id', $ds_id)->firstOrFail();
        $ds->status = 'corrected';
        $ds->save();

        //send email to user
        $mail = new CorrectionCorrectedMail($correctionRequest);
        Mail::to(User::find($correctionRequest->user_id)->email)->send($mail);

        return redirect()->route('home')->with('success', 'La correction a été envoyée avec succès');
    }

    // Méthode pour afficher le formulaire d'édition d'une demande de correction
    public function edit($ds_id)
    {
        $ds = DS::where('id', $ds_id)->firstOrFail();
        $correctionRequest = CorrectionRequest::where('ds_id', $ds_id)->firstOrFail();

        // Vérifier que la demande est en attente
        if ($correctionRequest->status !== 'pending') {
            return redirect()->route('correctionRequest.show', $ds_id)
                ->withErrors(['error' => 'Vous ne pouvez modifier qu\'une demande en attente de correction.']);
        }

        // Vérifier que l'utilisateur est bien le propriétaire
        if ($correctionRequest->user_id !== Auth::id()) {
            return redirect()->route('correctionRequest.show', $ds_id)
                ->withErrors(['error' => 'Vous ne pouvez pas modifier cette demande.']);
        }

        // Récupérer les images existantes via ImageManagementService
        $existingPictures = $this->imageManagementService->getFormattedImagesForComponent(
            context: 'corrections',
            identifier: 'ds-' . $ds_id,
            isPublic: false,
            pattern: 'student-*'
        );

        return view('correctionRequest.edit', compact('ds', 'correctionRequest', 'existingPictures'));
    }

    // Méthode pour mettre à jour une demande de correction
    public function update(UpdateCorrectionRequest $request, $ds_id)
    {
        $correctionRequest = CorrectionRequest::where('ds_id', $ds_id)->firstOrFail();

        // Vérifier que la demande est en attente
        if ($correctionRequest->status !== 'pending') {
            return redirect()->route('correctionRequest.show', $ds_id)
                ->withErrors(['error' => 'Vous ne pouvez modifier qu\'une demande en attente de correction.']);
        }

        // Vérifier que l'utilisateur est bien le propriétaire
        if ($correctionRequest->user_id !== Auth::id()) {
            return redirect()->route('correctionRequest.show', $ds_id)
                ->withErrors(['error' => 'Vous ne pouvez pas modifier cette demande.']);
        }

        // Gestion des images avec ImageManagementService
        $imagePaths = $this->imageManagementService->handleImageUpload(
            request: $request,
            inputName: 'pictures',
            deleteInputName: 'delete_pictures',
            context: 'corrections',
            identifier: 'ds-' . $ds_id,
            prefix: 'student-',
            isPublic: false
        );

        $correctionRequest->pictures = array_values($imagePaths);
        $correctionRequest->message = $request->message;
        $correctionRequest->save();

        return redirect()->route('correctionRequest.show', $ds_id)
            ->with('success', 'Votre demande de correction a été mise à jour avec succès');
    }

    // Méthode to destroy a correction request and his pictures and his folder
    public function destroyCorrectionRequest($ds_id)
    {
        $correctionRequest = CorrectionRequest::where('ds_id', $ds_id)->firstOrFail();
        $correctionRequest->delete();

        $ds = DS::where('id', $ds_id)->firstOrFail();
        $ds->status = 'finished';
        $ds->save();

        // Supprimer tout le dossier de correction avec FileUploadService
        $this->fileUploadService->deleteDirectory('corrections', 'ds-' . $ds_id, false);

        return redirect()->route('correctionRequest.index')->with('success', 'La demande de correction a été supprimée avec succès');
    }
}
