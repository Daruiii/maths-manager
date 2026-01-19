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

class CorrectionRequestController extends Controller
{
    protected \App\Services\FileUploadService $fileUploadService;

    public function __construct(\App\Services\FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
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

    // Méthode qui affichent les corrections en attente dans la page myCorrections
    // public function myCorrections(Request $request)
    // {
    //     $search = $request->get('search');
    //     $status = $request->get('status', 'pending'); // Par défaut, le statut est 'pending'

    //     $correctionRequests = CorrectionRequest::where('status', $status)
    //         ->when($search, function ($query, $search) {
    //             $query->whereHas('user', function ($query) use ($search) {
    //                 $query->where('name', 'LIKE', "%{$search}%");
    //             });
    //         })
    //         ->orderBy('created_at', 'desc')
    //         ->paginate(10)->withQueryString();

    //     // get all ds not_started and ongoing
    //     $ds = DS::join('users', 'users.id', '=', 'DS.user_id')
    //     ->where('status', 'not_started')
    //     ->orWhere('status', 'ongoing')
    //     ->orwhere('status', 'finished')
    //     ->select('DS.*', 'users.name')
    //     ->orderBy('users.name', 'asc')
    //     ->orderBy('status', 'asc')
    //     ->get();

    //     return view('correctionRequest.myCorrections', compact('correctionRequests', 'ds'));
    // }

    // Méthode to display the correction request form
    public function showCorrectionRequestForm($ds_id)
    {
        $ds = DS::where('id', $ds_id)->firstOrFail();
        return view('correctionRequest.requestForm', compact('ds'));
    }

    // Méthode to send a correction request
    public function sendCorrectionRequest(Request $request, $ds_id)
    {
        $request->validate([
            'pictures' => 'required|array|min:1',
            'pictures.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'message' => 'nullable|string|max:255',
        ]);

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

        $correctionRequest->pictures = json_encode($uploadedPaths);
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
        $pictures = json_decode($correctionRequest->pictures) ?? null;
        $correctedPictures = json_decode($correctionRequest->correction_pictures) ?? null;

        // Générer l'URL pour le PDF s'il existe
        $pdfUrl = null;
        if ($correctionRequest->correction_pdf) {
            $pdfParts = explode('/', $correctionRequest->correction_pdf);
            if (count($pdfParts) === 3) {
                $pdfUrl = route('private.file.serve', [
                    'context' => $pdfParts[0],
                    'identifier' => $pdfParts[1],
                    'filename' => $pdfParts[2]
                ]);
            }
        }

        return view('correctionRequest.show', compact('ds', 'correctionRequest', 'pictures', 'correctedPictures', 'corrector', 'pdfUrl'));
    }

    // Méthode pour formulaire de correction
    public function showCorrectionForm($ds_id)
    {
        $ds = DS::where('id', $ds_id)->firstOrFail();
        $correctionRequest = CorrectionRequest::where('ds_id', $ds_id)->firstOrFail();
        $pictures = json_decode($correctionRequest->pictures) ?? null;
        return view('correctionRequest.correctionForm', compact('ds', 'correctionRequest', 'pictures'));
    }

    // Méthode pour qu'un professeur puisse corriger une demande de correction
    public function correctCorrectionRequest(Request $request, $ds_id)
    {
        $request->validate([
            'correction_pictures.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'correction_pdf' => 'nullable|mimes:pdf',
            'grade' => 'required|numeric|min:0|max:20',
            'correction_message' => 'nullable|string|max:255',
        ]);

        $correctionRequest = CorrectionRequest::where('ds_id', $ds_id)->firstOrFail();
        $correctionRequest->status = 'corrected';
        $correctionRequest->grade = $request->grade;
        $correctionRequest->corrector_id = Auth::user()->id;
        $correctionRequest->correction_message = $request->correction_message;

        // Upload des images de correction en PRIVÉ
        if ($request->file('correction_pictures')) {
            $uploadedPaths = $this->fileUploadService->uploadMultiple(
                files: $request->file('correction_pictures'),
                context: 'corrections',
                identifier: 'ds-' . $ds_id,
                type: 'image',
                isPublic: false,  // PRIVÉ - correction visible uniquement par l'élève/prof
                prefix: 'corrected_'
            );
            $correctionRequest->correction_pictures = json_encode($uploadedPaths);
        }

        // Upload du PDF de correction en PRIVÉ
        if ($request->file('correction_pdf')) {
            // Supprimer l'ancien PDF s'il existe
            if ($correctionRequest->correction_pdf) {
                $this->fileUploadService->delete($correctionRequest->correction_pdf, false);
            }

            // Upload le nouveau PDF
            $pdfPath = $this->fileUploadService->upload(
                file: $request->file('correction_pdf'),
                context: 'corrections',
                identifier: 'ds-' . $ds_id,
                type: 'pdf',
                isPublic: false,  // PRIVÉ
                customName: 'correction'
            );
            $correctionRequest->correction_pdf = $pdfPath;
        }
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
