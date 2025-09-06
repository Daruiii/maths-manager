<x-guest-layout>
    @section('title', 'Accès Preprod - Maths Manager')
    
    <style>
        body { background: linear-gradient(135deg, #374151 0%, #1f2937 100%) !important; }
        /* Texte blanc pour cette vue */
        h1, .text-gray-900 { color: white !important; }
        .text-gray-600 { color: #d1d5db !important; }
    </style>
    
    <div class="text-center mb-6">
        <span class="inline-block bg-orange-500 text-white px-3 py-1 rounded-full text-sm font-bold">
            PREPROD
        </span>
    </div>

    @if(isset($error))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ $error }}
        </div>
    @endif

    <form method="GET">
        <div class="mb-4">
            <x-input-label for="preprod_password" :value="__('Mot de passe d\'accès')" />
            <x-text-input id="preprod_password" 
                class="block mt-1 w-full" 
                type="password" 
                name="preprod_password" 
                required 
                autofocus />
        </div>

        <div class="flex items-center justify-center">
            <x-primary-button class="w-full">
                {{ __('Accéder à la preprod') }}
            </x-primary-button>
        </div>
    </form>

    <div class="text-center text-sm text-gray-600 mt-4">
        Environnement de pré-production<br>
        Réservé aux développeurs
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('preprod_password').focus();
        });
    </script>
</x-guest-layout>
