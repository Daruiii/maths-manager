@extends('layouts.app')

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
                        <x-button-add href="{{ route('exercise.create', ['id' => $subchapter->id]) }}">Exercice</x-button-add>
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
                        class="exercise mb-8 bg-white rounded-lg box-shadow shadow-xl w-full md:w-8/12"
                        id="exercise-{{ $ex->id }}" data-order="{{ $ex->order }}">
                        <div class="p-4">
                            <div class="drag-handle hidden mr-2 cursor-move">☰</div>
                            @if ($ex->name)
                                <div class="flex row justify-end items-center h-2">
                                    {{-- <h2> #{{ $ex->id }}{{ $index + 1 }}</h2> --}}
                                    @auth @if (Auth::user()->role === 'admin')
                                        <div class="flex items-center space-x-2">
                                            <x-button-edit href="{{ route('exercise.edit', ['id' => $ex->id]) }}" />
                                            <x-button-delete href="{{ route('exercise.destroy', $ex->id) }}"
                                                entity="cet exercice" entityId="exercise{{ $ex->id }}" />
                                        </div>
                                    @endif @endauth
                                </div>

                                <div class="exercise-content text-sm px-4 cmu-serif">
                                    <h2 class="truncate font-bold text-sm exercise-title">
                                        <x-stars-difficulty starsActive="{{ $ex->difficulty }}" id="rating{{ $ex->id }}" />
                                        Exercice {{ $ex->order }}.
                                        {{ $ex->name }}</h2>
                                    {!! $ex->statement !!}
                                </div>
                            @else
                                @auth
                                    @if (Auth::user()->role === 'admin')
                                        <div class="flex row justify-end items-center h-2">
                                            <x-button-edit href="{{ route('exercise.edit', ['id' => $ex->id]) }}" />
                                            <x-button-delete href="{{ route('exercise.destroy', $ex->id) }}"
                                                entity="cet exercice" entityId="exercise{{ $ex->id }}" />
                                        </div>
                                    @endif
                                @endauth
                                <div class="exercise-content text-sm px-4 cmu-serif">
                                    <x-stars-difficulty starsActive="{{ $ex->difficulty }}" id="rating{{ $ex->id }}" />
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
                                            <button @click="showSolution = !showSolution" class=" flex row text-xs font-bold">
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
                                        @endif
                                    </div>

                                    <div x-show="showClue" class="bg-yellow-100 w-full p-2 rounded-lg">
                                        {{-- <h3 class="exercise-cc font-bold">Indice:</h3> --}}
                                        <div class="clue-content text-sm p-4 cmu-serif">
                                            {!! $ex->clue !!}
                                        </div>
                                    </div>
                                    <div x-show="showSolution" class="exercise-cc bg-red-100 w-full p-2 rounded-lg">
                                        {{-- <h3 class="exercise-cc font-bold">Correction:</h3> --}}
                                        <div class="solution-content text-sm p-4 cmu-serif">
                                            {!! $ex->solution !!}
                                        </div>
                                    </div>
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
        </script>
    @endsection
