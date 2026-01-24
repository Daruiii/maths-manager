<?php

namespace App\Http\Controllers\DS;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\DS;

class DSController extends Controller
{
    protected \App\Services\TimerFormattingService $timerService;
    protected \App\Services\QueryFiltersService $queryFiltersService;

    public function __construct(
        \App\Services\TimerFormattingService $timerService,
        \App\Services\QueryFiltersService $queryFiltersService
    ) {
        $this->timerService = $timerService;
        $this->queryFiltersService = $queryFiltersService;
    }

    // Méthode pour afficher tous les DS
    public function index(Request $request)
    {
        $sort_by_student = request()->query('sort_by_student');
        $sort_by_status = request()->query('sort_by_status');

        $dsList = DS::query()->with(['exercisesDS.multipleChapter', 'user', 'correctionRequest']);

        // Appliquer la recherche via le service
        $dsList = $this->queryFiltersService->applySearch(
            $dsList,
            $request->query('search'),
            ['type_bac', 'exercises_number', 'status']
        );

        // Recherche dans la relation user (si nécessaire, garder whereHas pour les relations)
        if ($request->query('search')) {
            $dsList->orWhereHas('user', function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->query('search') . '%');
            });
        }

        // Check if the request has sort_by_student
        if ($request->filled('sort_by_student')) {
            $dsList = $dsList->orderBy('user_id');
        }

        // Check if the request has sort_by_status
        if ($request->filled('sort_by_status')) {
            $dsList = $dsList->orderByRaw("FIELD(status, 'sent', 'ongoing', 'not_started', 'finished', 'corrected')");
        }

        // Default sort by created_at
        $dsList = $dsList->orderBy('created_at', 'desc');

        $dsList = $dsList->paginate(10)->withQueryString();

        return view('ds.index', compact('dsList', 'sort_by_student', 'sort_by_status'));
    }

    // Méthode pour afficher les DS de l'utilisateur connecté
    public function indexUser($id)
    {
        if (Auth::id() != $id) {
            return redirect()->route('ds.myDS', Auth::id());
        }
        // Eager loading pour éviter N+1 queries (fix #14.2)
        $dsList = DS::where('user_id', $id)
            ->with([
                'exercisesDS.multipleChapter',  // Charge exercices + leurs chapitres
                'correctionRequest'              // Charge les demandes de correction
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('ds.myDS', compact('dsList'));
    }

    // Méthode pour afficher un DS
    public function show($id)
    {
        $ds = DS::find($id);

        if (!$ds) {
            return redirect()->route('ds.myDS', Auth::id())->with('error', 'DS non trouvé.');
        }

        $timerFormatted = $this->timerService->format($ds->timer);
        $timerAction = "show";
        return view('ds.show', compact('ds', 'timerFormatted', 'timerAction'));
    }
}
