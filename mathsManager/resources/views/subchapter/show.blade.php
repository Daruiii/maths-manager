    @extends('layouts.app')

    @section('title', $subchapter->title . ' - Maths Manager')
    @section('meta_description', 'Sous-chapitre : ' . $subchapter->title . '. Description : ' . $subchapter->description)
    @section('canonical', url()->current())

    @section('content')
        <div class="container mx-auto">
            @auth
                @if (Auth::user()->role === 'admin')
                    <x-back-btn path="{{ route('classe.show', $subchapter->chapter->classe->level) }}"
                        theme="{{ $subchapter->chapter->theme }}"> Retour</x-back-btn>
                @else
                    <x-back-btn path="" theme="{{ $subchapter->chapter->theme }}"> Retour</x-back-btn>
                @endif
            @endauth
            <div
                class="flex flex-col align-center items-center justify-center my-5 bg-[#FBF7F0] w-full md:w-4/5 rounded-lg box-shadow shadow-xl">
                <div class="flex items-start justify-between w-full">
                    <div class="flex items-start justify-center align-start pr-12"
                        style="border-radius : 2rem 0 10rem 0 ; background-color: {{ $subchapter->chapter->theme }};">
                        <h1 class="text-white text-xl font-bold px-4 py-1">{{ $subchapter->title }}</h1>
                    </div>
                    @auth
                        @if (Auth::user()->role === 'admin')
                            <x-button-add
                                href="{{ route('exercise.create', ['id' => $subchapter->id]) }}">Exercice</x-button-add>
                        @endif
                    @endauth
                </div>
                <p class="text-xs px-4 py-1 w-full">{{ $subchapter->description }}</p>
                @auth
                    @if (Auth::user()->role === 'admin')
                        <button id="reorder-button"
                            class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700 focus:outline-none">Réorganiser</button>
                    @endif
                @endauth
                <div class=" md:p-4 flex flex-col items-center justify-center w-full" id="exercises-container">
                    @foreach ($exercises as $index => $ex)
                        <div x-data="{ showClue: false, showSolution: false }"
                            class="exercise mb-8 bg-white rounded-lg box-shadow shadow-xl w-full md:w-10/12"
                            id="exercise-{{ $ex->id }}" data-order="{{ $ex->order }}">
                            <div class="p-4">
                                <div class="drag-handle hidden mr-2 cursor-move">☰</div>
                                @if ($ex->name)
                                    <div class="flex row justify-end items-center h-2">
                                        {{-- <h2> #{{ $ex->id }}{{ $index + 1 }}</h2> --}}
                                        @auth @if (Auth::user()->role === 'admin')
                                            <div class="flex items-center space-x-2">
                                                @if ($ex->solution)
                                                    <a href="{{ route('exercise.whitelist.show', $ex->id) }}" 
                                                       class="inline-flex items-center px-2 py-1 border border-blue-300 rounded text-xs leading-4 font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors" title="Gérer les corrections">
                                                        🔒
                                                    </a>
                                                @endif
                                                <x-button-edit href="{{ route('exercise.edit', ['id' => $ex->id]) }}" />
                                                <x-button-delete href="{{ route('exercise.destroy', $ex->id) }}"
                                                    entity="cet exercice" entityId="exercise{{ $ex->id }}" />
                                            </div>
                                        @endif @endauth
                                    </div>

                                    <div class="exercise-content text-sm px-4 cmu-serif">
                                        <h2 class="truncate font-bold text-sm exercise-title">
                                            <x-stars-difficulty starsActive="{{ $ex->difficulty }}"
                                                id="rating{{ $ex->id }}" />
                                            Exercice {{ $ex->order }}.
                                            @if (stripos($ex->name, 'Bac') !== false || stripos($ex->name, 'bac') !== false)
                                                <span
                                                    class="bg-[#E67C7C] text-white px-2 py-1 rounded-full">{{ $ex->name }}</span>
                                            @else
                                                {{ $ex->name }}
                                            @endif
                                        </h2>
                                        {!! $ex->statement !!}
                                    </div>
                                @else
                                    @auth
                                        @if (Auth::user()->role === 'admin')
                                            <div class="flex row justify-end items-center h-2">
                                                <div class="flex items-center space-x-2">
                                                    @if ($ex->solution)
                                                        <a href="{{ route('exercise.whitelist.show', $ex->id) }}" 
                                                           class="inline-flex items-center px-2 py-1 border border-blue-300 rounded text-xs leading-4 font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors" title="Gérer les corrections">
                                                            🔒
                                                        </a>
                                                    @endif
                                                    <x-button-edit href="{{ route('exercise.edit', ['id' => $ex->id]) }}" />
                                                    <x-button-delete href="{{ route('exercise.destroy', $ex->id) }}"
                                                        entity="cet exercice" entityId="exercise{{ $ex->id }}" />
                                                </div>
                                            </div>
                                        @endif
                                    @endauth
                                    <div class="exercise-content text-sm px-4 cmu-serif">
                                        <x-stars-difficulty starsActive="{{ $ex->difficulty }}"
                                            id="rating{{ $ex->id }}" />
                                        <span class="truncate font-bold text-sm exercise-title">Exercice
                                            {{ $ex->order }}.</span> {!! $ex->statement !!}
                                    </div>
                                @endif
                            </div>
                            {{-- check if user verified = urue --}}
                            @auth
                                @if (Auth::user()->verified)
                                    <div class="border-t w-full p-2 rounded-b-lg border-gray-300">
                                        <div class="flex justify-between items-center">
                                            @if ($ex->clue)
                                                <button @click="showClue = !showClue" class=" flex row text-xs font-bold">
                                                    Indice
                                                    <svg :class="{ 'rotate-180': !showClue }" class="transition-transform"
                                                        width="15px" height="15px" viewBox="0 0 24 24" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round"
                                                            stroke-linejoin="round">
                                                        </g>
                                                        <g id="SVGRepo_iconCarrier">
                                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                                d="M7.00003 15.5C6.59557 15.5 6.23093 15.2564 6.07615 14.8827C5.92137 14.509 6.00692 14.0789 6.29292 13.7929L11.2929 8.79289C11.6834 8.40237 12.3166 8.40237 12.7071 8.79289L17.7071 13.7929C17.9931 14.0789 18.0787 14.509 17.9239 14.8827C17.7691 15.2564 17.4045 15.5 17 15.5H7.00003Z"
                                                                fill="#000000"></path>
                                                        </g>
                                                    </svg>
                                                </button>
                                            @else
                                                <div class=""></div>
                                            @endif
                                            @if ($ex->solution)
                                                @if (Auth::user()->role === 'admin' || Auth::user()->role === 'teacher' || $ex->isWhitelisted(Auth::id()))
                                                    <button @click="showSolution = !showSolution"
                                                        class=" flex row text-xs font-bold">
                                                        Correction
                                                        <svg :class="{ 'rotate-180': !showSolution }" class="transition-transform"
                                                            width="15px" height="15px" viewBox="0 0 24 24" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round"
                                                                stroke-linejoin="round">
                                                            </g>
                                                            <g id="SVGRepo_iconCarrier">
                                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                                    d="M7.00003 15.5C6.59557 15.5 6.23093 15.2564 6.07615 14.8827C5.92137 14.509 6.00692 14.0789 6.29292 13.7929L11.2929 8.79289C11.6834 8.40237 12.3166 8.40237 12.7071 8.79289L17.7071 13.7929C17.9931 14.0789 18.0787 14.509 17.9239 14.8827C17.7691 15.2564 17.4045 15.5 17 15.5H7.00003Z"
                                                                    fill="#000000"></path>
                                                            </g>
                                                        </svg>
                                                    </button>
                                                @else
                                                    <div class="text-xs text-red-600 font-bold flex items-center justify-end w-full space-x-1">
                                                        <span>🔒 Correction masquée</span>
                                                        @if (Auth::user()->role === 'student')
                                                            @if ($ex->hasWhitelistRequest(Auth::id()))
                                                                <span class="text-xs text-yellow-600 font-normal bg-yellow-100 px-2 py-1 rounded whitespace-nowrap">
                                                                    ⏳ En cours
                                                                </span>
                                                            @else
                                                                <button onclick="openRequestModal({{ $ex->id }}, '{{ $ex->name }}', {{ $ex->order }})" 
                                                                        class="text-xs bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded transition-colors whitespace-nowrap">
                                                                    Demander
                                                                </button>
                                                            @endif
                                                        @endif
                                                    </div>
                                                @endif
                                            @endif
                                        </div>

                                        <div x-show="showClue" x-transition:enter="transition ease-out duration-300"
                                            x-transition:enter-start="opacity-0 transform scale-95"
                                            x-transition:enter-end="opacity-100 transform scale-100"
                                            x-transition:leave="transition ease-in duration-200"
                                            x-transition:leave-start="opacity-100 transform scale-100"
                                            x-transition:leave-end="opacity-0 transform scale-95"
                                            class="bg-yellow-100 w-full p-2 rounded-lg">
                                            {{-- <h3 class="exercise-cc font-bold">Indice:</h3> --}}
                                            <div class="clue-content text-sm p-4 cmu-serif">
                                                {!! $ex->clue !!}
                                            </div>
                                        </div>
                                        @if (Auth::user()->role === 'admin' || Auth::user()->role === 'teacher' || $ex->isWhitelisted(Auth::id()))
                                            <div x-show="showSolution" x-transition:enter="transition ease-out duration-300"
                                                x-transition:enter-start="opacity-0 transform scale-95"
                                                x-transition:enter-end="opacity-100 transform scale-100"
                                                x-transition:leave="transition ease-in duration-200"
                                                x-transition:leave-start="opacity-100 transform scale-100"
                                                x-transition:leave-end="opacity-0 transform scale-95"
                                                class="exercise-cc bg-red-100 w-full p-2 rounded-lg">
                                                {{-- <h3 class="exercise-cc font-bold">Correction:</h3> --}}
                                                <div class="solution-content text-sm p-4 cmu-serif">
                                                    {!! $ex->solution !!}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            @endauth
                        </div>
                    @endforeach
                </div>
                <x-button-back-top />
            </div>
            <script>
                document.getElementById('reorder-button').addEventListener('click', function() {
                    var exercises = document.querySelectorAll('.exercise');
                    var minOrder = parseInt(exercises[0].dataset.order);
                    new Sortable(document.getElementById('exercises-container'), {
                        animation: 150,
                        // Update the order in the database when an item is moved
                        onEnd: function(evt) {
                            // Get the exercises again to reflect the new order
                            var exercises = document.querySelectorAll('.exercise');

                            // Send AJAX request to update order
                            var order = [];

                            for (var i = 0; i < exercises.length; i++) {
                                var id = exercises[i].id.replace('exercise-',
                                    ''); // Remove the 'exercise-' prefix
                                order.push({
                                    id: id,
                                    order: minOrder + i
                                }); // Start from the smallest order value
                            }

                            fetch('{{ route('exercises.updateOrder') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                        .getAttribute('content')
                                },
                                body: JSON.stringify({
                                    order: order
                                })
                            });
                        }
                    });
                });
                document.getElementById('reorder-button').addEventListener('click', function() {
                    this.classList.toggle('bg-blue-700');
                    this.classList.toggle('bg-green-500');
                    this.textContent = this.textContent === 'Réorganiser' ? 'Terminer' : 'Réorganiser';
                    document.querySelectorAll('.drag-handle').forEach(handle => handle.classList.toggle('hidden'));
                    if (this.textContent === 'Réorganiser') {
                        location.reload();
                    }
                });
                let scrollInterval;
            const scrollSpeed = 20; // Adjust scroll speed as needed
            const scrollThreshold = 50; // Distance from the edge to trigger scrolling

            document.addEventListener('dragover', function (event) {
                const mouseY = event.clientY;
                const windowHeight = window.innerHeight;

                if (mouseY < scrollThreshold) {
                    // Near the top of the window
                    clearInterval(scrollInterval);
                    scrollInterval = setInterval(() => {
                        window.scrollBy(0, -scrollSpeed);
                    }, 10);
                } else if (mouseY > windowHeight - scrollThreshold) {
                    // Near the bottom of the window
                    clearInterval(scrollInterval);
                    scrollInterval = setInterval(() => {
                        window.scrollBy(0, scrollSpeed);
                    }, 10);
                } else {
                    // Stop scrolling if not near the edges
                    clearInterval(scrollInterval);
                }
            });

            document.addEventListener('dragleave', function () {
                clearInterval(scrollInterval);
            });

            document.addEventListener('drop', function () {
                clearInterval(scrollInterval);
            });
            </script>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const params = new URLSearchParams(window.location.search);
                    const exerciseId = params.get('exercise');

                    if (exerciseId) {
                        const target = document.getElementById(`exercise-${exerciseId}`);
                        if (target) {
                            setTimeout(() => {
                                // Calculer la position pour scroller
                                const bodyHeight = document.body.scrollHeight;
                                const windowHeight = window.innerHeight;

                                // Si l'élément est trop bas, scroller jusqu'en bas
                                if (bodyHeight - target.offsetTop < windowHeight) {
                                    window.scrollTo({
                                        top: bodyHeight,
                                        behavior: 'smooth'
                                    });
                                } else {
                                    // Sinon, scroller à la position de l'élément
                                    const offset = -150; // Ajustez cette valeur si nécessaire
                                    const bodyRect = document.body.getBoundingClientRect().top;
                                    const elementRect = target.getBoundingClientRect().top;
                                    const elementPosition = elementRect - bodyRect;
                                    const offsetPosition = elementPosition + offset;

                                    window.scrollTo({
                                        top: offsetPosition,
                                        behavior: 'smooth'
                                    });
                                }

                                // Ajouter une classe pour mettre en évidence l'élément
                                target.classList.add('highlight');
                                setTimeout(() => target.classList.remove('highlight'),
                                10000); // Supprimer l'effet après 10 secondes
                            }, 500); // Attendre 500ms pour s'assurer que tout est chargé
                        }
                    }
                });
            </script>
            
            @if(Auth::check() && Auth::user()->role === 'student')
            <!-- Modal de demande de correction -->
            <div id="requestModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50" onclick="closeRequestModal(event)">
                <div class="flex items-center justify-center min-h-screen px-4">
                    <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6" onclick="event.stopPropagation()">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Demander l'accès à la correction</h3>
                            <button onclick="closeRequestModal()" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        
                        <form id="requestForm" method="POST">
                            @csrf
                            <p class="text-sm text-gray-600 mb-4">
                                Exercice : <strong id="exerciseName"></strong>
                            </p>
                            
                            <div class="mb-4">
                                <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                                    Pourquoi avez-vous besoin de cette correction ? (optionnel)
                                </label>
                                <textarea 
                                    name="message" 
                                    id="message" 
                                    rows="3" 
                                    maxlength="500"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Ex: Je n'arrive pas à comprendre cette méthode..."
                                ></textarea>
                                <p class="text-xs text-gray-500 mt-1">Un message aide l'équipe à mieux comprendre votre besoin.</p>
                            </div>
                            
                            <div class="flex justify-end space-x-3">
                                <button type="button" onclick="closeRequestModal()" 
                                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                    Annuler
                                </button>
                                <button type="submit" 
                                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    Envoyer ma demande
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <script>
            function openRequestModal(exerciseId, exerciseName, exerciseOrder) {
                document.getElementById('exerciseName').textContent = `${exerciseName} (n°${exerciseOrder})`;
                document.getElementById('requestForm').action = `/exercise/${exerciseId}/request-whitelist`;
                document.getElementById('message').value = '';
                document.getElementById('requestModal').classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }

            function closeRequestModal(event) {
                if (event && event.target !== event.currentTarget) return;
                document.getElementById('requestModal').classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }

            // Fermer la modal avec Escape
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeRequestModal();
                }
            });
            </script>
            @endif
        @endsection
