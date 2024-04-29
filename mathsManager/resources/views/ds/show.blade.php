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
            <button class="pause-btn" id="pauseButton">
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
            </button>
        @endif
        {{-- ask confirmation before redirecting the route --}}
        @if ($ds->status == 'ongoing')
            <button class="finish-btn" onclick="return confirm('Êtes-vous sûr de vouloir terminer ce DS ?');">
                <a href="{{ route('ds.finish', ['id' => $ds->id]) }}">
                    Terminer
                </a>
            </button>
        @endif
        <div
            class="flex flex-col align-center items-center justify-center my-5 bg-white w-full md:w-4/5 rounded-lg box-shadow shadow-xl">
            @auth
                @if (Auth::user()->role == 'admin')
                    <div class="flex items-center">
                        <a href="{{ route('ds.edit', ['id' => $ds->id]) }}" class="text-white font-bold py-2 px-4 rounded">
                            <svg width="15px" height="15px" viewBox="0 0 1024 1024" class="icon" version="1.1"
                                xmlns="http://www.w3.org/2000/svg" fill="#000000">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <path
                                        d="M823.3 938.8H229.4c-71.6 0-129.8-58.2-129.8-129.8V215.1c0-71.6 58.2-129.8 129.8-129.8h297c23.6 0 42.7 19.1 42.7 42.7s-19.1 42.7-42.7 42.7h-297c-24.5 0-44.4 19.9-44.4 44.4V809c0 24.5 19.9 44.4 44.4 44.4h593.9c24.5 0 44.4-19.9 44.4-44.4V512c0-23.6 19.1-42.7 42.7-42.7s42.7 19.1 42.7 42.7v297c0 71.6-58.2 129.8-129.8 129.8z"
                                        fill="#3688FF"></path>
                                    <path
                                        d="M483 756.5c-1.8 0-3.5-0.1-5.3-0.3l-134.5-16.8c-19.4-2.4-34.6-17.7-37-37l-16.8-134.5c-1.6-13.1 2.9-26.2 12.2-35.5l374.6-374.6c51.1-51.1 134.2-51.1 185.3 0l26.3 26.3c24.8 24.7 38.4 57.6 38.4 92.7 0 35-13.6 67.9-38.4 92.7L513.2 744c-8.1 8.1-19 12.5-30.2 12.5z m-96.3-97.7l80.8 10.1 359.8-359.8c8.6-8.6 13.4-20.1 13.4-32.3 0-12.2-4.8-23.7-13.4-32.3L801 218.2c-17.9-17.8-46.8-17.8-64.6 0L376.6 578l10.1 80.8z"
                                        fill="#5F6379"></path>
                                </g>
                            </svg></a>
                        <form action="{{ route('ds.destroy', $ds->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-white font-bold py-2 px-4 rounded"
                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce DS ?');">
                                <svg height="12px" width="12px" version="1.1" id="Layer_1"
                                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    viewBox="0 0 512.001 512.001" xml:space="preserve" fill="#000000">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path style="fill:#FF757C;"
                                            d="M495.441,72.695L439.306,16.56c-8.498-8.498-22.278-8.498-30.777,0L271.389,153.7 c-8.498,8.498-22.278,8.498-30.777,0L103.472,16.56c-8.498-8.498-22.278-8.498-30.777,0L16.56,72.695 c-8.498,8.498-8.498,22.278,0,30.777l137.14,137.14c8.498,8.498,8.498,22.278,0,30.777L16.56,408.529 c-8.498,8.498-8.498,22.278,0,30.777l56.136,56.136c8.498,8.498,22.278,8.498,30.777,0l137.14-137.14 c8.498-8.498,22.278-8.498,30.777,0l137.14,137.14c8.498,8.498,22.278,8.498,30.777,0l56.136-56.136 c8.498-8.498,8.498-22.278,0-30.777l-137.14-137.139c-8.498-8.498-8.498-22.278,0-30.777l137.14-137.14 C503.941,94.974,503.941,81.194,495.441,72.695z">
                                        </path>
                                        <g>
                                            <path style="fill:#4D4D4D;"
                                                d="M88.084,511.999c-8.184,0-16.369-3.115-22.6-9.346L9.347,446.518 c-12.462-12.462-12.462-32.739,0-45.201l137.14-137.14c4.508-4.508,4.508-11.843,0-16.351L9.347,110.685 c-12.462-12.463-12.462-32.74,0-45.201L65.482,9.348c12.464-12.462,32.74-12.462,45.201,0l137.141,137.14 c4.508,4.508,11.843,4.508,16.351,0l137.14-137.14c12.461-12.461,32.738-12.462,45.2,0l56.138,56.136 c12.462,12.462,12.462,32.739,0,45.201l-137.14,137.14c-4.508,4.508-4.508,11.843,0,16.351l137.14,137.14 c12.462,12.463,12.462,32.74,0,45.201l-56.136,56.136c-12.464,12.462-32.74,12.462-45.201,0l-137.141-137.14 c-4.508-4.508-11.843-4.508-16.351,0l-137.14,137.14C104.454,508.884,96.268,511.999,88.084,511.999z M88.084,20.391 c-2.961,0-5.922,1.127-8.177,3.381L23.772,79.908c-4.508,4.508-4.508,11.844,0,16.352l137.14,137.139 c12.462,12.462,12.462,32.739,0,45.201l-137.14,137.14c-4.508,4.508-4.508,11.844,0,16.351l56.136,56.137 c4.508,4.508,11.843,4.507,16.351,0l137.14-137.14c12.463-12.463,32.739-12.463,45.201,0l137.14,137.139 c4.508,4.509,11.842,4.508,16.352,0l56.135-56.136c4.508-4.508,4.508-11.844,0-16.352L351.089,278.602 c-12.462-12.462-12.462-32.739,0-45.201l137.14-137.14c4.508-4.508,4.508-11.844,0-16.351l0,0l-56.136-56.136 c-4.509-4.507-11.844-4.507-16.351,0l-137.14,137.139c-12.463,12.463-32.739,12.463-45.201,0L96.259,23.772 C94.005,21.518,91.045,20.391,88.084,20.391z">
                                            </path>
                                            <path style="fill:#4D4D4D;"
                                                d="M88.935,473.447c-2.611,0-5.22-0.996-7.212-2.988c-3.983-3.983-3.983-10.442,0-14.426l82.476-82.475 c3.984-3.983,10.441-3.983,14.426,0c3.983,3.983,3.983,10.442,0,14.426L96.148,470.46 C94.155,472.452,91.545,473.447,88.935,473.447z">
                                            </path>
                                            <path style="fill:#4D4D4D;"
                                                d="M195.201,367.181c-2.611,0-5.22-0.996-7.212-2.987c-3.983-3.983-3.983-10.442,0-14.426l6.873-6.873 c3.984-3.983,10.44-3.983,14.426,0c3.983,3.983,3.983,10.442,0,14.426l-6.873,6.873 C200.421,366.184,197.812,367.181,195.201,367.181z">
                                            </path>
                                        </g>
                                    </g>
                                </svg></button>
                        </form>
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
                            gestion du temps et les exigences de l'épreuve. Merci de traiter ce devoir avec sérieux, en<br>
                            respectant le temps imparti et en soignant votre présentation. N'oubliez pas d'espacer vos<br>
                            équations et d'encadrer vos résultats. La calculatrice est autorisée. <br>
                            Veuillez envoyer votre copie à la fin pour correction.
                        </p>
                    </div>
                @elseif ($ds->harder_exercises)
                    <div class="flex items-center justify-center align-center">
                        <p class="cmu-ti text-sm text-center">Ce sujet est une simulation de devoir surveillé difficile pour
                            vous aider à maîtriser la gestion du<br>
                            temps et les exigences du supérieur. Merci de traiter ce devoir avec sérieux, en respectant
                            le<br>
                            temps imparti et en soignant votre présentation. N'oubliez pas d'espacer vos équations et<br>
                            d'encadrer vos résultats. La calculatrice est autorisée.<br>
                            Veuillez envoyer votre copie `a la fin pour correction.
                        </p>
                    </div>
                @else
                    <div class="flex items-center justify-center align-center">
                        <p class="cmu-ti text-sm text-center">Ce sujet est une simulation de devoir surveillé de difficulté moyenne (niveau bac) pour vous aider<br>
                            à maîtriser la gestion du temps et tester vos connaissances. Merci de traiter ce devoir avec<br>
                            sérieux, en respectant le temps imparti et en soignant votre présentation. N'oubliez pas d'espacer<br>
                            vos équations et d'encadrer vos résultats. La calculatrice est autorisée.<br>
                            Veuillez envoyer votre copie à la fin pour correction.
                            
                        </p>
                @endif
            </div>
            {{-- ici affichage de chaque exo à la suite --}}
            <div class="w-9/12 flex flex-col items-start justify-start">
                @foreach ($ds->exercisesDS as $index => $exercise)
                    <div class="mb-16 w-full" id="exercise-{{ $index + 1 }}">
                        <div class="w-full exercise-content text-sm cmu-serif">
                            <span class="truncate font-bold text-sm exercise-title"> Exercice {{ $index + 1 }}.</span>
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
            fetch("{{ route('ds.pause', ['id' => $ds->id, 'timer' => 'timerValue']) }}".replace('timerValue', timerValue), {
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
