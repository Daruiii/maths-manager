<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CorrectionRequest;
use App\Models\DS;
use Illuminate\Support\Facades\Auth;

class CorrectionRequestController extends Controller
{
    private function destroyCorrectionFolder($id)
    {
        // there is a folder correction in the folder
        // $path = public_path('storage/correctionRequests/' . $id . '/correction');
        // $path2 = public_path('storage/correctionRequests/' . $id);
        $path = file_exists(public_path('storage/correctionRequests/' . $id . '/correction')) ? public_path('storage/correctionRequests/' . $id . '/correction') : null;
        $path2 = file_exists(public_path('storage/correctionRequests/' . $id)) ? public_path('storage/correctionRequests/' . $id) : null;
         // foreach path != null, delete the content of the folder and the folder
        if ($path != null) {
            $files = glob($path . '/*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
            rmdir($path);
        }
        if ($path2 != null) {
            $files = glob($path2 . '/*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
            rmdir($path2);
        }
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
    public function myCorrections(Request $request)
    {
        $search = $request->get('search');
    
        $correctionRequests = CorrectionRequest::where('status', 'pending')
            ->when($search, function ($query, $search) {
                $query->whereHas('user', function ($query) use ($search) {
                    $query->where('name', 'LIKE', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)->withQueryString();
    
        return view('correctionRequest.myCorrections', compact('correctionRequests'));
    }

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
            'pictures.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $ds = DS::where('id', $ds_id)->firstOrFail();
        if ($correctionRequest = CorrectionRequest::where('ds_id', $ds_id)->first()) {
            return redirect()->route('ds.myDS', Auth::user()->id)->with('error', 'You have already sent a correction request for this DS');
        }
        $correctionRequest = new CorrectionRequest();
        $correctionRequest->user_id = auth()->user()->id;
        $correctionRequest->ds_id = $ds->id;
        $correctionRequest->status = 'pending';
        $correctionRequest->pictures = 'null'; // to avoid 'Array to string conversion' error
        $correctionRequest->grade = 0;
        $correctionRequest->save();

        $imagesPaths = [];
        // si le folder 'correctionRequests/ds_id' existe supprimer son contenu et le folder
            $this->destroyCorrectionFolder($ds_id);

        foreach ($request->file('pictures') as $key => $image) {
            $img_name = $key + 1 . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('storage/correctionRequests/' . $ds_id);
            $image->move($destinationPath, $img_name);
            $imagesPaths[] = 'correctionRequests/' . $ds_id . '/' . $img_name;
        }
        $correctionRequest->pictures = json_encode($imagesPaths);
        $correctionRequest->save();

        // set ds status to 'sent'
        $ds->status = 'sent';
        $ds->save();

        return redirect()->route('ds.myDS', Auth::user()->id)->with('success', 'Votre demande de correction a été envoyée avec succès');
    }

    // Méthode for show the correction request
    public function showCorrectionRequest($ds_id)
    {
        $ds = DS::where('id', $ds_id)->firstOrFail();
        $correctionRequest = CorrectionRequest::where('ds_id', $ds_id)->firstOrFail();
        $pictures = json_decode($correctionRequest->pictures) ?? null;
        $correctedPictures = json_decode($correctionRequest->correction_pictures) ?? null;   
        return view('correctionRequest.show', compact('ds', 'correctionRequest', 'pictures', 'correctedPictures'));
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
            'correction_pictures.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'grade' => 'required|numeric|min:0|max:20',
            'correction_message' => 'nullable',
        ]);

        $correctionRequest = CorrectionRequest::where('ds_id', $ds_id)->firstOrFail();
        $correctionRequest->status = 'corrected';
        $correctionRequest->grade = $request->grade;
        $correctionRequest->correction_message = $request->correction_message;

        $correctionsImagesPaths = [];

        if ($request->file('correction_pictures')) {
        if (file_exists(public_path('storage/correctionRequests/' . $ds_id . '/correction'))) {
            $this->destroyCorrectionFolder($ds_id);
        }
        
        foreach ($request->file('correction_pictures') as $key => $image) {
            // enregistrer l'image dans le path 'public/storage/correctionRequests/ds_id/correction/1.jpg' then 2.jpg, 3.jpg, ...
            $img_name = $key + 1 . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('storage/correctionRequests/' . $ds_id . '/correction');
            $image->move($destinationPath, $img_name);
            $correctionsImagesPaths[] = 'correctionRequests/' . $ds_id . '/correction/' . $img_name;
        }
        $correctionRequest->correction_pictures = json_encode($correctionsImagesPaths);
    }
        $correctionRequest->save();

        // set ds status to 'finished'
        $ds = DS::where('id', $ds_id)->firstOrFail();
        $ds->status = 'corrected';
        $ds->save();
        // with search user_name
        return redirect()->route('correctionRequest.index', ['search' => $ds->user->name]);
    }

    // Méthode to destroy a correction request and his pictures and his folder
    public function destroyCorrectionRequest($ds_id)
    {
        $correctionRequest = CorrectionRequest::where('ds_id', $ds_id)->firstOrFail();
        $correctionRequest->delete();

        $ds = DS::where('id', $ds_id)->firstOrFail();
        $ds->status = 'finished';
        $ds->save();

        $this->destroyCorrectionFolder($ds_id);

        return redirect()->route('ds.myDS', Auth::user()->id);
    }
}
