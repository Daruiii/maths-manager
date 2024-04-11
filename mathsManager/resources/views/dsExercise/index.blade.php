@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex justify-between items-center pt-6">
            <div>
                <h2 class="text-lg leading-6 font-medium text-gray-900">DS Exercices</h2>
            </div>
            {{-- add  exercise button --}}
            <div>
                <a href="{{ route('ds_exercise.create') }}"
                    class="px-4 py-2 text-sm text-white bg-green-500 rounded hover:bg-green-600 focus:outline-none">Ajouter un exercice</a>
            </div>
        </div>
               {{-- Search form --}}
               <div class="flex justify-between items-center py-2">
                <form method="GET" action="{{ route('ds_exercises.index') }}" class="flex space-x-4">
                    <input type="text" name="search" class="form-input rounded-md shadow-sm mt-1 block w-full"
                        placeholder="Rechercher un exercice...">
                    <button type="submit"
                        class="px-4 py-2 text-sm text-white bg-blue-500 rounded hover:bg-blue-600 focus:outline-none">Rechercher</button>
                </form>
                <form method="GET" action="{{ route('ds_exercises.index') }}" class="flex space-x-4">
                    <select name="multiple_chapter_id" class="form-select rounded-md shadow-sm mt-1 block">
                        <option value="">Tous les chapitres multiples</option>
                        @foreach ($multipleChapters as $chapter)
                            <option value="{{ $chapter->id }}">{{ $chapter->title }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="px-4 py-2 text-sm text-white bg-blue-500 rounded hover:bg-blue-600 focus:outline-none">Filtrer</button>
                </form>
            </div>
        <div class="flex flex-col my-8">
            <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                <div
                    class="align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200">
                    <table class="min-w-full">
                        <thead>
                            <tr>
                                <th
                                    class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    Numéro</th>
                                <th
                                    class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    Nom</th>
                                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                        Chapitres associés
                                    </th>
                                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                        Chap de tri
                                    </th>
                                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                        Inj
                                    </th>
                                <th
                                    class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @foreach ($dsExercises as $ex)
                                <tr>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 font-medium text-gray-900">
                                        {{ $ex->id }}</td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                        {{ $ex->name }}</td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                            @foreach ($ex->chapters as $chapter)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full truncate"
                                                style="background-color: {{ $chapter->theme }}; color: black;">
                                                    {{ $chapter->title }}
                                                </span>
                                            @endforeach
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                            @foreach ($multipleChapters as $chapter)
                                            @if ($chapter->id === $ex->multiple_chapter_id)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    {{ $chapter->title }}
                                                </span>
                                            @endif
                                            @endforeach
                                        </td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                        @if ($ex->harder_exercise)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Oui
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Non
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-right text-sm leading-5 font-medium">
                                        <div class="flex justify-center items-center gap-2">
                                            <a href="{{ route('ds_exercise.show', $ex->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                                <svg version="1.1" id="Uploaded to svgrepo.com" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20px" height="20px" viewBox="0 0 32 32" xml:space="preserve" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <style type="text/css"> .linesandangles_een{fill:#111918;} </style> <path class="linesandangles_een" d="M28.895,15.553c-0.131-0.261-2.517-4.919-7.279-7.248c-0.007-0.006-0.012-0.014-0.019-0.02 l-0.006,0.007C19.995,7.514,18.133,7,16,7c-2.133,0-3.996,0.515-5.592,1.291l-0.006-0.006c-0.006,0.006-0.012,0.013-0.018,0.019 c-4.762,2.329-7.148,6.987-7.279,7.248L2.882,16l0.224,0.447C3.28,16.796,7.48,25,16,25s12.72-8.204,12.895-8.553L29.118,16 L28.895,15.553z M20.468,10.005C21.455,11.106,22,12.507,22,14c0,3.309-2.691,6-6,6s-6-2.691-6-6c0-1.493,0.545-2.894,1.532-3.995 C12.836,9.4,14.323,9,16,9C17.678,9,19.164,9.4,20.468,10.005z M16,23c-6.215,0-9.893-5.39-10.853-7 c0.442-0.742,1.468-2.284,3.046-3.733C8.069,12.83,8,13.409,8,14c0,4.411,3.589,8,8,8s8-3.589,8-8c0-0.591-0.069-1.17-0.193-1.733 c1.578,1.449,2.604,2.991,3.046,3.733C25.893,17.61,22.215,23,16,23z M12,14c0-2.209,1.791-4,4-4s4,1.791,4,4c0,2.209-1.791,4-4,4 S12,16.209,12,14z"></path> </g>
                                                </svg>
                                            </a>
                                            <a href="{{ route('ds_exercise.edit', $ex->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                                <svg width="15px" height="15px" viewBox="0 0 1024 1024" class="icon" version="1.1" xmlns="http://www.w3.org/2000/svg" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M823.3 938.8H229.4c-71.6 0-129.8-58.2-129.8-129.8V215.1c0-71.6 58.2-129.8 129.8-129.8h297c23.6 0 42.7 19.1 42.7 42.7s-19.1 42.7-42.7 42.7h-297c-24.5 0-44.4 19.9-44.4 44.4V809c0 24.5 19.9 44.4 44.4 44.4h593.9c24.5 0 44.4-19.9 44.4-44.4V512c0-23.6 19.1-42.7 42.7-42.7s42.7 19.1 42.7 42.7v297c0 71.6-58.2 129.8-129.8 129.8z" fill="#3688FF"></path><path d="M483 756.5c-1.8 0-3.5-0.1-5.3-0.3l-134.5-16.8c-19.4-2.4-34.6-17.7-37-37l-16.8-134.5c-1.6-13.1 2.9-26.2 12.2-35.5l374.6-374.6c51.1-51.1 134.2-51.1 185.3 0l26.3 26.3c24.8 24.7 38.4 57.6 38.4 92.7 0 35-13.6 67.9-38.4 92.7L513.2 744c-8.1 8.1-19 12.5-30.2 12.5z m-96.3-97.7l80.8 10.1 359.8-359.8c8.6-8.6 13.4-20.1 13.4-32.3 0-12.2-4.8-23.7-13.4-32.3L801 218.2c-17.9-17.8-46.8-17.8-64.6 0L376.6 578l10.1 80.8z" fill="#5F6379"></path></g>
                                                </svg>
                                            </a>
                                            <form action="{{ route('ds_exercise.destroy', $ex->id) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet exercice ?')"
                                                class="text-red-600 hover:text-red-900">
                                             <svg height="12px" width="12px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512.001 512.001" xml:space="preserve" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path style="fill:#FF757C;" d="M495.441,72.695L439.306,16.56c-8.498-8.498-22.278-8.498-30.777,0L271.389,153.7 c-8.498,8.498-22.278,8.498-30.777,0L103.472,16.56c-8.498-8.498-22.278-8.498-30.777,0L16.56,72.695 c-8.498,8.498-8.498,22.278,0,30.777l137.14,137.14c8.498,8.498,8.498,22.278,0,30.777L16.56,408.529 c-8.498,8.498-8.498,22.278,0,30.777l56.136,56.136c8.498,8.498,22.278,8.498,30.777,0l137.14-137.14 c8.498-8.498,22.278-8.498,30.777,0l137.14,137.14c8.498,8.498,22.278,8.498,30.777,0l56.136-56.136 c8.498-8.498,8.498-22.278,0-30.777l-137.14-137.139c-8.498-8.498-8.498-22.278,0-30.777l137.14-137.14 C503.941,94.974,503.941,81.194,495.441,72.695z"></path> <g> <path style="fill:#4D4D4D;" d="M88.084,511.999c-8.184,0-16.369-3.115-22.6-9.346L9.347,446.518 c-12.462-12.462-12.462-32.739,0-45.201l137.14-137.14c4.508-4.508,4.508-11.843,0-16.351L9.347,110.685 c-12.462-12.463-12.462-32.74,0-45.201L65.482,9.348c12.464-12.462,32.74-12.462,45.201,0l137.141,137.14 c4.508,4.508,11.843,4.508,16.351,0l137.14-137.14c12.461-12.461,32.738-12.462,45.2,0l56.138,56.136 c12.462,12.462,12.462,32.739,0,45.201l-137.14,137.14c-4.508,4.508-4.508,11.843,0,16.351l137.14,137.14 c12.462,12.463,12.462,32.74,0,45.201l-56.136,56.136c-12.464,12.462-32.74,12.462-45.201,0l-137.141-137.14 c-4.508-4.508-11.843-4.508-16.351,0l-137.14,137.14C104.454,508.884,96.268,511.999,88.084,511.999z M88.084,20.391 c-2.961,0-5.922,1.127-8.177,3.381L23.772,79.908c-4.508,4.508-4.508,11.844,0,16.352l137.14,137.139 c12.462,12.462,12.462,32.739,0,45.201l-137.14,137.14c-4.508,4.508-4.508,11.844,0,16.351l56.136,56.137 c4.508,4.508,11.843,4.507,16.351,0l137.14-137.14c12.463-12.463,32.739-12.463,45.201,0l137.14,137.139 c4.508,4.509,11.842,4.508,16.352,0l56.135-56.136c4.508-4.508,4.508-11.844,0-16.352L351.089,278.602 c-12.462-12.462-12.462-32.739,0-45.201l137.14-137.14c4.508-4.508,4.508-11.844,0-16.351l0,0l-56.136-56.136 c-4.509-4.507-11.844-4.507-16.351,0l-137.14,137.139c-12.463,12.463-32.739,12.463-45.201,0L96.259,23.772 C94.005,21.518,91.045,20.391,88.084,20.391z"></path> <path style="fill:#4D4D4D;" d="M88.935,473.447c-2.611,0-5.22-0.996-7.212-2.988c-3.983-3.983-3.983-10.442,0-14.426l82.476-82.475 c3.984-3.983,10.441-3.983,14.426,0c3.983,3.983,3.983,10.442,0,14.426L96.148,470.46 C94.155,472.452,91.545,473.447,88.935,473.447z"></path> <path style="fill:#4D4D4D;" d="M195.201,367.181c-2.611,0-5.22-0.996-7.212-2.987c-3.983-3.983-3.983-10.442,0-14.426l6.873-6.873 c3.984-3.983,10.44-3.983,14.426,0c3.983,3.983,3.983,10.442,0,14.426l-6.873,6.873 C200.421,366.184,197.812,367.181,195.201,367.181z"></path> </g> 
                                                    </g></svg>
                                            </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
