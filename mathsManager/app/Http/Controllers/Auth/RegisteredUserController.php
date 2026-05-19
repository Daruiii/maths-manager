<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Rules\ValidEmailDomain;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    protected \App\Services\FileUploadService $fileUploadService;

    public function __construct(\App\Services\FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }
    /**
     * Display the registration view.
     */
    public function create(Request $request): Response
    {
        // Préserver le redirect pour le flow d'invitation
        $redirectUrl = $request->query('redirect');
        if ($redirectUrl) {
            redirect()->setIntendedUrl($redirectUrl);
        }

        return Inertia::render('Auth/Register', [
            'redirectUrl' => $redirectUrl,
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class, new ValidEmailDomain()],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ]);
        
        $userAvatar = 'default.jpg';
        if ($request->hasFile('avatar')) {
            $avatarPath = $this->fileUploadService->upload(
                file: $request->file('avatar'),
                context: 'images',
                identifier: '',
                type: 'image',
                isPublic: true,
                customName: str_replace(['@', '.'], '-', $request->email)
            );
            $userAvatar = basename($avatarPath);
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'avatar' => $userAvatar,
        ]);

        event(new Registered($user));

        Auth::login($user);

        $user->sendEmailVerificationNotification();

        return redirect()->intended(route('home', absolute: false));
    }
}
