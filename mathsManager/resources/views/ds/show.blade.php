@extends('layouts.app')

@section('content')
    <div class="container mx-auto">
        {{-- fixed div with timer inside --}}
        <div class="timer">
            <div class="timer-div">
                <p class="timer-text" id="timer">{{ $timerFormatted }}</p>
            </div>
        </div>

        @if ($timerAction == 'start')
            {{-- <button class="pause-btn" id="pauseButton">
                <svg width="24px" height="24px" viewBox="0 0 24.00 24.00" fill="none" xmlns="http://www.w3.org/2000/svg"
                    stroke="#ffffff">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                    <g id="SVGRepo_iconCarrier">
                        <path
                            d="M15 5V19M21 5V19M3 7.20608V16.7939C3 17.7996 3 18.3024 3.19886 18.5352C3.37141 18.7373 3.63025 18.8445 3.89512 18.8236C4.20038 18.7996 4.55593 18.4441 5.26704 17.733L10.061 12.939C10.3897 12.6103 10.554 12.446 10.6156 12.2565C10.6697 12.0898 10.6697 11.9102 10.6156 11.7435C10.554 11.554 10.3897 11.3897 10.061 11.061L5.26704 6.26704C4.55593 5.55593 4.20038 5.20038 3.89512 5.17636C3.63025 5.15551 3.37141 5.26273 3.19886 5.46476C3 5.69759 3 6.20042 3 7.20608Z"
                            stroke="#000000" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"></path>
                    </g>
                </svg>
            </button> --}}
            <x-button-pause />
        @else
            @auth
                @if (Auth::user()->role == 'student')
                    <x-back-btn path="{{ route('correctionRequest.show', ['ds_id' => $ds->id]) }}" />
                @endif
            @endauth
        @endif
                @if ($ds->status == 'ongoing')
                    <form method="GET" class="finish-btn" action="{{ route('ds.finish', ['id' => $ds->id]) }}" >
                        @csrf
                        <button type="submit" onclick="if (!confirm('Êtes-vous sûr de vouloir terminer ce DS ?')) return false;" class="btn btn-primary">
                            Terminer
                        </button>
                    </form> 
                @endif
                <div
                    class="flex flex-col align-center items-center justify-center my-5 bg-white w-full md:w-4/5 rounded-lg box-shadow shadow-xl">
                    @auth
                        @if (Auth::user()->role == 'admin')
                            <div class="flex items-center">
                                <x-button-edit href="{{ route('ds.edit', ['id' => $ds->id]) }}" />
                                <x-button-delete href="{{ route('ds.destroy', $ds->id) }}" entity="ce DS" />
                            </div>
                        @endif
                    @endauth
                    <div class="flex flex-col items-center justify-center mt-24 mb-16 gap-8 w-3/4" id="entete">
                        <div class="flex flex-col items-center justify-center">
                            <h1 class="text-lg text-center cmu-serif uppercase">S<span class="text-sm">ujet de ds</span></h1>
                            <h1 class="text-lg text-center cmu-serif uppercase">M<span class="text-sm ">athematiques</span></h1>
                            <h1 class="text-lg text-center cmu-serif uppercase">T<span class="text-sm ">erminale</span> S<span
                                    class="text-sm ">pecialité</span></h1>
                        </div>
                        @if ($ds->type_bac)
                            <div class="flex items-center justify-center align-center">
                                <p class="cmu-ti text-sm text-center">Ce sujet est une simulation rigoureuse de l'examen du
                                    baccalauréat pour vous
                                    aider à maîtriser la<br>
                                    gestion du temps et les exigences de l'épreuve. Merci de traiter ce devoir avec sérieux,
                                    en<br>
                                    respectant le temps imparti et en soignant votre présentation. N'oubliez pas d'espacer
                                    vos<br>
                                    équations et d'encadrer vos résultats. La calculatrice est autorisée. <br>
                                    Veuillez envoyer votre copie à la fin pour correction.
                                </p>
                            </div>
                        @elseif ($ds->harder_exercises)
                            <div class="flex items-center justify-center align-center">
                                <p class="cmu-ti text-sm text-center">Ce sujet est une simulation de devoir surveillé difficile
                                    pour
                                    vous aider à maîtriser la gestion du<br>
                                    temps et les exigences du supérieur. Merci de traiter ce devoir avec sérieux, en respectant
                                    le<br>
                                    temps imparti et en soignant votre présentation. N'oubliez pas d'espacer vos équations
                                    et<br>
                                    d'encadrer vos résultats. La calculatrice est autorisée.<br>
                                    Veuillez envoyer votre copie `a la fin pour correction.
                                </p>
                            </div>
                        @else
                            <div class="flex items-center justify-center align-center">
                                <p class="cmu-ti text-sm text-center">Ce sujet est une simulation de devoir surveillé de
                                    difficulté moyenne (niveau bac) pour vous aider<br>
                                    à maîtriser la gestion du temps et tester vos connaissances. Merci de traiter ce devoir
                                    avec<br>
                                    sérieux, en respectant le temps imparti et en soignant votre présentation. N'oubliez pas
                                    d'espacer<br>
                                    vos équations et d'encadrer vos résultats. La calculatrice est autorisée.<br>
                                    Veuillez envoyer votre copie à la fin pour correction.

                                </p>
                        @endif
                    </div>
                    {{-- ici affichage de chaque exo à la suite --}}
                    <div class="w-9/12 flex flex-col items-start justify-start">
                        @foreach ($ds->exercisesDS as $index => $exercise)
                            <div class="mb-16 w-full" id="exercise-{{ $index + 1 }}">
                                <div class="exercise-content text-sm cmu-serif min-w-full">
                                    <span class="truncate font-bold text-sm exercise-title"> Exercice
                                        {{ $index + 1 }}.</span>
                                    {!! $exercise->statement !!}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
        </div>
        @if ($timerAction == 'start')
            <script>
                var timer = document.getElementById('timer');
                var time = "{{ $timerFormatted }}";
                var interval;

                function updateTimer() {
                    updateTimerWithAjax();
                    window.location.href = "{{ route('ds.myDS', ['id' => Auth::user()->id]) }}";
                }

                function updateTimerWithAjax() {
                    var timerValue = timer.textContent; // Utiliser textContent au lieu de innerText
                    fetch("{{ route('ds.pause', ['id' => $ds->id, 'timer' => 'timerValue']) }}".replace('timerValue',
                        timerValue), {
                            method: 'GET',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(response => response.json())
                        .then(data => console.log(data))
                        .catch(error => console.error(error));
                }

                // on reloading the page, the timer will be updated
                window.addEventListener('beforeunload', function(event) {
                    updateTimerWithAjax();
                });

                // on going back to ohter page, the timer will be updated
                window.addEventListener('popstate', function(event) {
                    updateTimerWithAjax();
                });

                // on click back button of the navigator, the timer will be updated
                window.addEventListener('unload', function(event) {
                    updateTimerWithAjax();
                });

                function startTimer() {
                    interval = setInterval(function() {
                        var timerArray = time.split(':');
                        var hours = parseInt(timerArray[0]);
                        var minutes = parseInt(timerArray[1]);
                        var seconds = parseInt(timerArray[2]);

                        if (hours == 0 && minutes == 0 && seconds == 0) {
                            clearInterval(interval);
                            window.location.href = "{{ route('ds.pause', ['id' => $ds->id, 'timer' => '00:00:00']) }}";
                        } else {
                            if (seconds == 0) {
                                if (minutes == 0) {
                                    hours--;
                                    minutes = 59;
                                    seconds = 59;
                                } else {
                                    minutes--;
                                    seconds = 59;
                                }
                            } else {
                                seconds--;
                            }
                        }

                        time = (hours < 10 ? '0' + hours : hours) + ':' + (minutes < 10 ? '0' + minutes : minutes) + ':' +
                            (seconds < 10 ? '0' + seconds : seconds);
                        timer.innerHTML = time;
                    }, 1000);
                }

                document.getElementById('pauseButton').addEventListener('click', function() {
                    clearInterval(interval);
                    updateTimer();
                });

                // Démarrer le timer lors du chargement de la page
                window.addEventListener('load', function() {
                    startTimer();
                });
            </script>
        @endif
    @endsection
