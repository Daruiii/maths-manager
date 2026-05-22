<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use App\Models\Classe;
use App\Models\DS;
use App\Models\Td;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user(),
            ],
            'appName' => config('app.name'),
            'appEnv' => config('app.env'),
            'classes' => Classe::where('hidden', false)->orderBy('display_order')->get(),
            'dsNotStarted' => auth()->check() ? DS::where('user_id', auth()->id())->where('status', 'not_started')->count() : 0,
            'tdNotStarted' => auth()->check() ? Td::where('user_id', auth()->id())->where('status', 'not_started')->count() : 0,
            'notifications' => fn () => ($user = $request->user()) ? [
                'unread_count' => $user->unreadNotifications()->count(),
                'recent' => $user->notifications()->latest()->take(8)->get()->map(fn ($n) => [
                    'id'         => $n->id,
                    'type'       => $n->data['type'] ?? null,
                    'data'       => $n->data,
                    'read_at'    => $n->read_at?->toIso8601String(),
                    'created_at' => $n->created_at->toIso8601String(),
                ]),
            ] : null,
            'flash' => [
                'success'  => fn () => $request->session()->get('success'),
                'error'    => fn () => $request->session()->get('error'),
                'warning'  => fn () => $request->session()->get('warning'),
                'info'     => fn () => $request->session()->get('info'),
                'confetti' => fn () => $request->session()->get('confetti'),
            ],
        ]);
    }
}
