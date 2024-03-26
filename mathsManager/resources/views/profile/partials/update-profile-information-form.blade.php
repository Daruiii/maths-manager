<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')
        {{-- Avatar --}}
        <div class="relative w-32 h-32 rounded-full overflow-hidden">
            @if (Str::startsWith($user->avatar, 'http'))
                <img src="{{ $user->avatar }}" alt="Avatar" class="absolute inset-0 w-full h-full object-cover"
                    id="imagePreview">
                <label for="avatar" class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 opacity-0 hover:opacity-100 cursor-pointer transition duration-300 ease-in-out text-white" style="pointer-events: none;">
                    <span class="text-white font-semibold">EDIT</span>
                    <input type="file" id="avatar" name="avatar" class="hidden" onchange="document.getElementById('imagePreview').src = window.URL.createObjectURL(this.files[0])">
                </label>
            @else
                <img src="{{ asset('storage/images/' . $user->avatar) }}" alt="Avatar" class="absolute inset-0 w-full h-full object-cover" id="imagePreview">
                <label for="avatar" class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 opacity-0 hover:opacity-100 cursor-pointer transition duration-300 ease-in-out text-white">
                    <span class="text-white font-semibold">EDIT</span>
                    <input type="file" id="avatar" name="avatar" class="hidden" onchange="document.getElementById('imagePreview').src = window.URL.createObjectURL(this.files[0])">
                </label>
            @endif
        </div>
        <button type="button" class="mt-2 text-sm bg-red-500 text-white py-1 px-3 rounded hover:bg-red-700 transition-colors" onclick="removeAvatar()">Supprimer l'image</button>
        <x-input-error class="mt-2" :messages="$errors->get('avatar')" />

        {{-- Name --}}
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)"
                required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        {{-- Email --}}
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)"
                required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification"
                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>

<script>
    function removeAvatar() {
        // Réinitialise l'input file
        document.getElementById('avatar').value = "";
        // Masque l'aperçu de l'image ou le remplace par une image par défaut
        document.getElementById('imagePreview').src = '{{ asset('storage/images/default.jpg') }}';
        // mettre à jour le contenu de avatar
        document.getElementById('avatar').value = 'default.jpg';
    }
</script>
