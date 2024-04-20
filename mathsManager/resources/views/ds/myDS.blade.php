@extends('layouts.app')

@section('content')
    {{-- Titre de la page un peu sur la gauche avec ecrit "Mes devoirs" --}}
    {{-- un bouton pour générer un nouveau DS --}}
    <div class="container mx-auto mb-8">
        <div class="flex justify-start flex-col align-center w-full my-8 ms-12">
            <h1 class="text-xl cmu-bold">Mes devoirs</h1>
            <div class="flex row justify-start align-center">
                <a href="{{ route('ds.create') }}" class="flex items-center justify-center mr-3">
                    <svg width="15px" height="15px" viewBox="0 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg"
                        xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns"
                        fill="#000000">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <title>plus-circle</title>
                            <desc>Created with Sketch Beta.</desc>
                            <defs> </defs>
                            <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"
                                sketch:type="MSPage">
                                <g id="Icon-Set" sketch:type="MSLayerGroup"
                                    transform="translate(-464.000000, -1087.000000)" fill="#000000">
                                    <path
                                        d="M480,1117 C472.268,1117 466,1110.73 466,1103 C466,1095.27 472.268,1089 480,1089 C487.732,1089 494,1095.27 494,1103 C494,1110.73 487.732,1117 480,1117 L480,1117 Z M480,1087 C471.163,1087 464,1094.16 464,1103 C464,1111.84 471.163,1119 480,1119 C488.837,1119 496,1111.84 496,1103 C496,1094.16 488.837,1087 480,1087 L480,1087 Z M486,1102 L481,1102 L481,1097 C481,1096.45 480.553,1096 480,1096 C479.447,1096 479,1096.45 479,1097 L479,1102 L474,1102 C473.447,1102 473,1102.45 473,1103 C473,1103.55 473.447,1104 474,1104 L479,1104 L479,1109 C479,1109.55 479.447,1110 480,1110 C480.553,1110 481,1109.55 481,1109 L481,1104 L486,1104 C486.553,1104 487,1103.55 487,1103 C487,1102.45 486.553,1102 486,1102 L486,1102 Z"
                                        id="plus-circle" sketch:type="MSShapeGroup"> </path>
                                </g>
                            </g>
                        </g>
                    </svg>
                </a>
                <a href="{{ route('ds.create') }}"
                    class="bg-blue-100 rounded-lg p-2 link w-44 text-center hover:bg-blue-200 shadow-md transition duration-300">
                    Générer un DS
            </a>
        </div>
        </div>
        @foreach ($dsList as $ds)
        {{-- admin --}}
        @if (Auth::user()->role == 'admin')
            <div class="flex justify-end px-3 flex-row-reverse items-center w-5/6 gap-2 ">
                <a href="{{ route('ds.edit', $ds->id) }}"
                    class="text-indigo-600 hover:text-indigo-900">
                    <svg width="15px" height="15px" viewBox="0 0 1024 1024" class="icon" version="1.1" xmlns="http://www.w3.org/2000/svg" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M823.3 938.8H229.4c-71.6 0-129.8-58.2-129.8-129.8V215.1c0-71.6 58.2-129.8 129.8-129.8h297c23.6 0 42.7 19.1 42.7 42.7s-19.1 42.7-42.7 42.7h-297c-24.5 0-44.4 19.9-44.4 44.4V809c0 24.5 19.9 44.4 44.4 44.4h593.9c24.5 0 44.4-19.9 44.4-44.4V512c0-23.6 19.1-42.7 42.7-42.7s42.7 19.1 42.7 42.7v297c0 71.6-58.2 129.8-129.8 129.8z" fill="#3688FF"></path><path d="M483 756.5c-1.8 0-3.5-0.1-5.3-0.3l-134.5-16.8c-19.4-2.4-34.6-17.7-37-37l-16.8-134.5c-1.6-13.1 2.9-26.2 12.2-35.5l374.6-374.6c51.1-51.1 134.2-51.1 185.3 0l26.3 26.3c24.8 24.7 38.4 57.6 38.4 92.7 0 35-13.6 67.9-38.4 92.7L513.2 744c-8.1 8.1-19 12.5-30.2 12.5z m-96.3-97.7l80.8 10.1 359.8-359.8c8.6-8.6 13.4-20.1 13.4-32.3 0-12.2-4.8-23.7-13.4-32.3L801 218.2c-17.9-17.8-46.8-17.8-64.6 0L376.6 578l10.1 80.8z" fill="#5F6379"></path></g>
                    </svg>
                </a>
                <form action="{{ route('ds.destroy', $ds->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce DS ?')"
                        class="text-red-600 hover:text-red-900">
                          <svg height="12px" width="12px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512.001 512.001" xml:space="preserve" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path style="fill:#FF757C;" d="M495.441,72.695L439.306,16.56c-8.498-8.498-22.278-8.498-30.777,0L271.389,153.7 c-8.498,8.498-22.278,8.498-30.777,0L103.472,16.56c-8.498-8.498-22.278-8.498-30.777,0L16.56,72.695 c-8.498,8.498-8.498,22.278,0,30.777l137.14,137.14c8.498,8.498,8.498,22.278,0,30.777L16.56,408.529 c-8.498,8.498-8.498,22.278,0,30.777l56.136,56.136c8.498,8.498,22.278,8.498,30.777,0l137.14-137.14 c8.498-8.498,22.278-8.498,30.777,0l137.14,137.14c8.498,8.498,22.278,8.498,30.777,0l56.136-56.136 c8.498-8.498,8.498-22.278,0-30.777l-137.14-137.139c-8.498-8.498-8.498-22.278,0-30.777l137.14-137.14 C503.941,94.974,503.941,81.194,495.441,72.695z"></path> <g> <path style="fill:#4D4D4D;" d="M88.084,511.999c-8.184,0-16.369-3.115-22.6-9.346L9.347,446.518 c-12.462-12.462-12.462-32.739,0-45.201l137.14-137.14c4.508-4.508,4.508-11.843,0-16.351L9.347,110.685 c-12.462-12.463-12.462-32.74,0-45.201L65.482,9.348c12.464-12.462,32.74-12.462,45.201,0l137.141,137.14 c4.508,4.508,11.843,4.508,16.351,0l137.14-137.14c12.461-12.461,32.738-12.462,45.2,0l56.138,56.136 c12.462,12.462,12.462,32.739,0,45.201l-137.14,137.14c-4.508,4.508-4.508,11.843,0,16.351l137.14,137.14 c12.462,12.463,12.462,32.74,0,45.201l-56.136,56.136c-12.464,12.462-32.74,12.462-45.201,0l-137.141-137.14 c-4.508-4.508-11.843-4.508-16.351,0l-137.14,137.14C104.454,508.884,96.268,511.999,88.084,511.999z M88.084,20.391 c-2.961,0-5.922,1.127-8.177,3.381L23.772,79.908c-4.508,4.508-4.508,11.844,0,16.352l137.14,137.139 c12.462,12.462,12.462,32.739,0,45.201l-137.14,137.14c-4.508,4.508-4.508,11.844,0,16.351l56.136,56.137 c4.508,4.508,11.843,4.507,16.351,0l137.14-137.14c12.463-12.463,32.739-12.463,45.201,0l137.14,137.139 c4.508,4.509,11.842,4.508,16.352,0l56.135-56.136c4.508-4.508,4.508-11.844,0-16.352L351.089,278.602 c-12.462-12.462-12.462-32.739,0-45.201l137.14-137.14c4.508-4.508,4.508-11.844,0-16.351l0,0l-56.136-56.136 c-4.509-4.507-11.844-4.507-16.351,0l-137.14,137.139c-12.463,12.463-32.739,12.463-45.201,0L96.259,23.772 C94.005,21.518,91.045,20.391,88.084,20.391z"></path> <path style="fill:#4D4D4D;" d="M88.935,473.447c-2.611,0-5.22-0.996-7.212-2.988c-3.983-3.983-3.983-10.442,0-14.426l82.476-82.475 c3.984-3.983,10.441-3.983,14.426,0c3.983,3.983,3.983,10.442,0,14.426L96.148,470.46 C94.155,472.452,91.545,473.447,88.935,473.447z"></path> <path style="fill:#4D4D4D;" d="M195.201,367.181c-2.611,0-5.22-0.996-7.212-2.987c-3.983-3.983-3.983-10.442,0-14.426l6.873-6.873 c3.984-3.983,10.44-3.983,14.426,0c3.983,3.983,3.983,10.442,0,14.426l-6.873,6.873 C200.421,366.184,197.812,367.181,195.201,367.181z"></path> </g> 
                        </g></svg>
                    </button>
                </form>
            </div>
        @endif
            <div class="flex justify-start flex-col items-center w-5/6 mb-3 bg-white shadow-md rounded-lg p-4">
                <div class="flex justify-between items-center w-full p-2">
                    <h2 class="text-sm font-bold">DS du {{ $ds->created_at->format('d/m/Y') }}.</h2>
                    @php
                        $hours = 0;
                        $time = $ds->time;
                        while ($time > 60) {
                            $hours++;
                            $time -= 60;
                        }
                        // get the timer time hours:minutes:seconds
                        $timerHours = 0;
                        $timerMinutes = 0;
                        $timer = $ds->timer;
                        while ($timer > 3600) {
                            $timerHours++;
                            $timer -= 3600;
                        }
                        while ($timer > 60) {
                            $timerMinutes++;
                            $timer -= 60;
                        }
                    @endphp
                    <h2 class="text-sm">{{ $ds->exercises_number }} exercice{{ $ds->exercises_number > 1 ? 's' : '' }} -
                        {{ $hours ? $hours . 'h' : '' }} {{ $time ? $time . 'min' : '' }}</h2>
                    {{--  si status break, ongoing, finish, finishedLate, correction_in_progress, corrected --}}

                    @if ($ds->status == 'not_started')
                        <a href="{{ route('ds.start', $ds->id) }}"
                            class="bg-blue-100 rounded-lg p-2 text-sm link w-auto text-center hover:bg-blue-200 shadow-md transition duration-300">
                            Commencer
                        </a>
                        @elseif ($ds->status == 'ongoing')
                        {{-- chrono != 0 --}}
                            @if ($ds->chrono != 0)
                                <h2 class="text-sm"> + {{ $ds->chrono }} secondes</h2>
                            @endif
                            <h2 class="text-sm">{{ $timerHours ? $timerHours . 'h' : '' }} {{ $timerMinutes ? $timerMinutes . 'min et' : '' }} {{ $timer ? $timer . 's restantes' : '' }}</h2> 
                            <a href="{{ route('ds.start', $ds->id) }}"
                            class="bg-blue-100 rounded-lg p-2 text-sm link w-auto text-center hover:bg-blue-200 shadow-md transition duration-300">
                            Continuer
                            </a>
                        @elseif ($ds->status == 'finished')
                        <a href="{{ route('ds.show', $ds->id) }}"
                            class="bg-blue-100 rounded-lg p-2 text-sm link w-auto text-center hover:bg-blue-200 shadow-md transition duration-300">
                            Envoyer pour correction
                        </a>
                        @elseif ($ds->status == 'sent')
                            <h2
                                class="bg-blue-100 rounded-lg p-2 text-sm link w-auto text-center hover:bg-blue-200 shadow-md transition duration-300">
                                Correction en cours ...
                            </h2>
                        @elseif ($ds->status == 'corrected')
                            {{-- href="{{ route('ds.show', $ds->id) }}" --}}
                            <h2 class="text-sm">
                                note / 20
                            </h2>
                            <a href=""
                                class="bg-blue-100 rounded-lg p-2 link w-auto text-center hover:bg-blue-200 shadow-md transition duration-300">
                                Voir la correction
                            </a>
                    @endif
                </div>
                <div class="flex justify-between items-end w-full">
                    <div class="flex gap-2 ">
                        @foreach ($ds->exercisesDS as $exercise)
                            <h3 class="text-xs cmu font-bold p-1 rounded-lg w-auto text-center vertical-center hover:bg-blue-200 shadow-md transition duration-300 truncate"
                                style="background-color: {{ $exercise->multipleChapter->theme }};">
                                {{ $exercise->multipleChapter->title }}</h3>
                        @endforeach
                    </div>
                    @if ($ds->type_bac)
                        <h2 class="text-xs font-bold">Type Bac </h2>
                    @endif
                    @if ($ds->harder_exercises)
                        <h2 class="text-xs font-bold">Approfondissement</h2>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
    {{-- reload the page one time when we arrive on the page --}}

@endsection
