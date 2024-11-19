@extends('layouts.app')

@section('content')
    <div class="container mx-auto mb-8">
        <div class="flex justify-start flex-col items-start w-9/12 mt-6 mb-4">
            <div class="flex flex-row items-center justify-center gap-2">
                <h1 class="text-xl">Liste des élèves</h1>
                <button id="helpButton" class="">
                    <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <path opacity="0.1"
                                d="M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z"
                                fill="#323232"></path>
                            <path
                                d="M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z"
                                stroke="#323232" stroke-width="2"></path>
                            <path
                                d="M10.5 8.67709C10.8665 8.26188 11.4027 8 12 8C13.1046 8 14 8.89543 14 10C14 10.9337 13.3601 11.718 12.4949 11.9383C12.2273 12.0064 12 12.2239 12 12.5V12.5V13"
                                stroke="#323232" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M12 16H12.01" stroke="#323232" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"></path>
                        </g>
                    </svg>
                </button>
            </div>
            <div id="helpModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white p-4 rounded-lg w-10/12 max-w-lg">
                    <!-- Contenu de la popup -->
                    <p class="text-lg font-semibold w-full text-center">Aide</p>
                    <p class="text-sm">Cette page vous permet de gérer les élèves de votre classe. Vous pouvez leur assigner des devoirs surveillés, des fiches d'exercices, activer ou désactiver leur compte, et plus encore.</p>
                    <p class="text-sm mt-2">Les boutons suivants sont disponibles pour chaque élève :</p>
                    <ul class="list-none text-sm mt-2">
                        <li class="mb-2 bg-gray-200 p-2 rounded-lg">
                            <a href="#" class="text-white bg-blue-700 hover:bg-blue-900 rounded-lg px-4 py-2 w-full text-center inline-block">
                                {{ __('Assigner un DS') }}
                            </a>
                            <p class="mt-1"><strong>Assigner un DS</strong> : Permet d'assigner un devoir surveillé à l'élève.</p>
                        </li>
                        <li class="mb-2 bg-gray-200 p-2 rounded-lg">
                            <a href="#" class="text-white bg-green-700 hover:bg-green-900 rounded-lg px-4 py-2 w-full text-center inline-block">
                                {{ __('Assigner une fiche') }}
                            </a>
                            <p class="mt-1"><strong>Assigner une fiche</strong> : Permet d'assigner une fiche d'exercices à l'élève.</p>
                        </li>
                        <li class="mb-2 bg-gray-200 p-2 rounded-lg">
                            <button type="button" class="text-white bg-gray-500 rounded-full px-2 py-1 cursor-not-allowed inline-block">
                                DS+
                            </button>
                            <p class="mt-1"><strong>DS+</strong> : Ce bouton permet de réinitialiser la date du dernier devoir surveillé généré pour l'élève. Il est activé uniquement si un devoir surveillé a été généré aujourd'hui.</p>
                        </li>
                        <li class="mb-2 bg-gray-200 p-2 rounded-lg">
                            <button type="button" class="text-xs text-red-600 hover:text-red-900 p-2 border border-red-600 rounded-full bg-red-100 inline-block">
                                Désactiver
                            </button>
                            <p class="mt-1"><strong>Activer/Désactiver</strong> : Permet d'activer ou de désactiver le compte de l'élève. Si le compte est actif, le bouton affichera "Désactiver", sinon il affichera "Activer".</p>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="flex justify-between items-center py-3">
                <x-search-bar-admin action="{{ route('students.show') }}" placeholder="Rechercher un utilisateur..."
                    name="search" />
            </div>
            <div class="w-full flex flex-wrap justify-center items-center gap-3">
                @foreach ($students as $student)
                    <x-user-card :avatar="$student->avatar" :name="$student->name" :assignDsRoute="route('ds.assign', ['student_id' => $student->id])" :assignSheetRoute="route('exercises_sheet.selectChapter', ['student_id' => $student->id])" :lastDsGenerated="$student->last_ds_generated_at"
                        :resetLastDsRoute="route('user.resetLastDSGeneratedAt', $student->id)" :verified="$student->verified" :id="$student->id" />
                @endforeach
            </div>

        </div>
        {{ $students->links() }}
    </div>

    <script>
        document.getElementById('helpButton').addEventListener('click', function() {
            document.getElementById('helpModal').classList.remove('hidden');
        });

        window.addEventListener('click', function(event) {
            const modal = document.getElementById('helpModal');
            if (event.target === modal) {
                modal.classList.add('hidden');
            }
        });
    </script>
@endsection
