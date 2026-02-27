<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\TeacherApprovedMail;
use App\Mail\TeacherInviteMail;
use App\Mail\TeacherRejectedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class TeacherApplicationController extends Controller
{
    /**
     * Display a listing of teacher applications.
     */
    public function index()
    {
        // Load pending applications with user profile
        $applications = User::where('role', 'teacher')
            ->where('status', 'pending_approval')
            ->orderBy('created_at', 'asc')
            ->get();

        return Inertia::render('Admin/TeacherApplications', [
            'applications' => $applications,
        ]);
    }

    /**
     * Approve a teacher application.
     */
    public function approve(Request $request, User $user)
    {
        if ($user->role !== 'teacher' || $user->status !== 'pending_approval') {
            return back()->with('error', 'Cette candidature ne peut pas être approuvée.');
        }

        $user->update(['status' => 'active']);

        $user->teacherApplication()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'status' => 'approved',
                'reviewed_by' => $request->user()->id,
                'reviewed_at' => now(),
            ]
        );

        Mail::to($user->email)->send(new TeacherApprovedMail($user));

        return back()->with('success', 'La candidature de ' . $user->name . ' a été approuvée avec succès.');
    }

    /**
     * Send a Calendly invite to a pending teacher application.
     */
    public function invite(Request $request, User $user)
    {
        if ($user->role !== 'teacher' || $user->status !== 'pending_approval') {
            return back()->with('error', 'L\'invitation ne peut être envoyée que pour les statuts en attente.');
        }

        $user->update([
            'calendly_invite_sent' => true,
            'calendly_invite_sent_at' => now(),
        ]);

        Mail::to($user->email)->send(new TeacherInviteMail($user));

        return back()->with('success', 'L\'invitation Calendly a été envoyée à ' . $user->name . '.');
    }

    /**
     * Reject a teacher application.
     */
    public function reject(Request $request, User $user)
    {
        if ($user->role !== 'teacher' || $user->status !== 'pending_approval') {
            return back()->with('error', 'Cette candidature ne peut pas être refusée.');
        }

        $request->validate([
            'admin_notes' => 'nullable|string',
        ]);

        $user->update(['status' => 'rejected']);

        $user->teacherApplication()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'status' => 'rejected',
                'reviewed_by' => $request->user()->id,
                'reviewed_at' => now(),
                'admin_notes' => $request->input('admin_notes'),
            ]
        );

        Mail::to($user->email)->send(new TeacherRejectedMail($user));

        return back()->with('success', 'La candidature de ' . $user->name . ' a été refusée.');
    }

    /**
     * Ban a user (from teacher applications view).
     */
    public function ban(User $user)
    {
        $user->update(['status' => 'banned']);

        return back()->with('success', 'L\'utilisateur ' . $user->name . ' a été banni.');
    }
}
