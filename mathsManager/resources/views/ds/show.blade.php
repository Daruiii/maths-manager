@extends('layouts.app')

@section('content')
    <div class="container mx-auto">
        <div class="flex justify-between w-full mt-5 p-2">
            <a href="{{ route('ds.myDS') }}"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Faire une pause</a>
            <div class="flex items-center">
                <a href="{{ route('ds.edit', ['id' => $ds->id]) }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Modifier</a>
                <form action="{{ route('ds.destroy', $ds->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce DS ?');">Supprimer</button>
                </form>
            </div>
        </div>
        <div
            class="flex flex-col align-center items-center justify-center my-5 bg-white w-4/5 rounded-lg box-shadow shadow-xl">
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
                @else
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
                @endif
            </div>
            {{-- ici affichage de chaque exo à la suite --}}
            <div class="w-2/3 flex flex-col items-start justify-center">
                @foreach ($ds->exercisesDS as $index => $exercise)
                    <div class="mb-16" id="exercise-{{ $index + 1 }}">
                            <div class="exercise-content text-sm px-4 cmu-serif">
                                <span class="truncate font-bold text-sm exercise-title"> Exercice {{ $index + 1 }}.</span> {!! $exercise->statement !!}
                            </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
