<?php

namespace App\Http\Controllers\DS;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\DS;

class DSPlayController extends Controller
{
    protected \App\Services\TimerFormattingService $timerService;

    public function __construct(\App\Services\TimerFormattingService $timerService)
    {
        $this->timerService = $timerService;
    }

    // Méthode pour démarrer un DS
    public function start($id)
    {
        $ds = DS::find($id);
        $timerFormatted = $this->timerService->format($ds->timer);
        $ds->status = "ongoing";
        $ds->save();
        $timerAction = "start";

        return view('ds.show', compact('ds', 'timerAction', 'timerFormatted'));
    }

    // Méthode pour mettre en pause un DS
    public function pause($id, $timer)
    {
        $timerInSeconds = $this->timerService->parseToSeconds($timer);
        $ds = DS::find($id);
        $ds->timer = $timerInSeconds;
        // timer = 0 means the DS is finished so set status to finished
        if ($timerInSeconds == 0) {
            $ds->status = "finished";
        }
        $ds->save();
        $timerAction = "pause";
        return response()->json(['timerAction' => $timerAction, 'ds' => $ds]);
    }

    public function finish($id)
    {
        $ds = DS::find($id);
        $ds->status = "finished";
        $ds->timer = 0;
        $ds->save();
        return redirect()->route('ds.myDS', Auth::id());
    }
}
