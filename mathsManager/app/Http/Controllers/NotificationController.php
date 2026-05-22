<?php

namespace App\Http\Controllers;

use App\Models\CorrectionRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function redirect(string $id): RedirectResponse
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        $data = $notification->data;

        $type = $data['type'] ?? null;

        if ($type === 'correction_sent' && !empty($data['correction_id'])) {
            $correction = CorrectionRequest::find($data['correction_id']);
            if ($correction) {
                return redirect($correction->dm_id
                    ? '/dm/' . $correction->dm_id
                    : '/ds/' . $correction->ds_id
                );
            }
        }

        if ($type === 'td_unlocked' && !empty($data['td_id'])) {
            return redirect('/td/' . $data['td_id']);
        }

        if ($type === 'correction_submitted' && !empty($data['correction_id'])) {
            return redirect(route('teacher.corrections.show', $data['correction_id']));
        }

        if ($type === 'unlock_requested') {
            if (!empty($data['batch_id'])) {
                return redirect(route('teacher.assignations.show', ['type' => 'td', 'batch' => $data['batch_id']]));
            }
            return redirect(route('teacher.bureau.devoirs'));
        }

        if ($type === 'work_assigned' && !empty($data['link'])) {
            return redirect($data['link']);
        }

        if (!empty($data['link'])) {
            return redirect($data['link']);
        }

        return redirect('/');
    }

    public function readAll(): RedirectResponse
    {
        Auth::user()->unreadNotifications->markAsRead();

        return back();
    }
}
