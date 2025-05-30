@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-back-btn path="{{ route('admin') }}"> Retour</x-back-btn>

        <div class="flex justify-center items-center pt-6">
            <div>
                <h2 class="text-lg leading-6 font-medium text-gray-900">Exercices de DS ({{ $dsExercises->total() }})</h2>
            </div>
            <div>
                <x-button-add href="{{ route('ds_exercise.create') }}">Exercice</x-button-add>
            </div>
        </div>
        <div class="flex justify-between items-center py-2 w-full flex-wrap gap-2">
            {{-- Search form --}}
            <div>
                <x-search-bar-admin action="{{ route('ds_exercises.index') }}" placeholder="Rechercher un exercice..."
                    name="search" />
            </div>

            {{-- Combined filter form --}}
            <form method="GET" action="{{ route('ds_exercises.index') }}" class="flex gap-2">
                @csrf
                {{-- Filter for type --}}
                <div class="relative group rounded-lg overflow-hidden flex items-center justify-end">
                    <select name="type"
                        class="bg-none hover:placeholder-shown:bg-green-500 text-blue-400 bg-transparent ring-0 outline-none border border-gray-500 text-gray-900 placeholder-blue-700 text-sm font-bold rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 appearance-none w-40"
                        onchange="this.form.submit()">
                        <option value="" {{ !$typeFilterActivated ? 'selected' : '' }}>Tous les types</option>
                        @if ($typeFilterActivated)
                            <option value="{{ $typeActivated }}" selected>
                                {{ ucfirst($typeActivated) }}
                            </option>
                        @endif
                        @foreach (['bac', 'mimigl', 'lycee', 'concours'] as $type)
                            @if ($typeFilterActivated && $type === $typeActivated)
                                @continue
                            @endif
                            <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-2">
                        <svg class="w-4 h-4 text-gray-500 pointer-events-none" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>

                {{-- Filter for academy --}}
                <div class="relative group rounded-lg overflow-hidden flex items-center justify-end">
                    <select name="academy"
                        class="bg-none hover:placeholder-shown:bg-green-500 text-blue-400 bg-transparent ring-0 outline-none border border-gray-500 text-gray-900 placeholder-blue-700 text-sm font-bold rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 appearance-none w-48"
                        onchange="this.form.submit()">
                        <option value="" {{ !$academyFilterActivated ? 'selected' : '' }}>Toutes les académies
                        </option>
                        @if ($academyFilterActivated)
                            <option value="{{ $academyActivated }}" selected>
                                {{ ucfirst($academyActivated) }}
                            </option>
                        @endif
                        @foreach ($academies as $academy)
                            @if ($academyFilterActivated && $academy === $academyActivated)
                                @continue
                            @endif
                            <option value="{{ $academy }}">{{ ucfirst($academy) }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-2">
                        <svg class="w-4 h-4 text-gray-500 pointer-events-none" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>

                {{-- Filter for multiple chapters --}}
                <div class="relative group rounded-lg overflow-hidden flex items-center justify-end">
                    <select name="multiple_chapter_id"
                        class="bg-none hover:placeholder-shown:bg-green-500 text-blue-400 bg-transparent ring-0 outline-none border border-gray-500 text-gray-900 placeholder-blue-700 text-sm font-bold rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 appearance-none"
                        onchange="this.form.submit()">
                        <option value="" {{ !$filterActivated ? 'selected' : '' }}>Tous les chapitres</option>
                        @if ($filterActivated)
                            <option value="{{ $chapterActivated->id }}" selected>
                                {{ $chapterActivated->title }}
                            </option>
                        @endif
                        @foreach ($multipleChapters as $index => $chapter)
                            @if ($filterActivated && $chapter->id === $chapterActivated->id)
                                @continue
                            @endif
                            <option value="{{ $chapter->id }}">{{ $chapter->title }}
                                ({{ $chapter->dsExercises->where('harder_exercise', false)->count() }} |
                                {{ $chapter->dsExercises->where('harder_exercise', true)->count() }} inj)
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-2">
                        <svg class="w-4 h-4 text-gray-500 pointer-events-none" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>

                {{-- Reset button --}}
                @if ($typeFilterActivated || $academyFilterActivated || $filterActivated)
                    <a href="{{ route('ds_exercises.index') }}"
                        class="hover:rotate-180 duration-300 z-10 flex items-center justify-center text-gray-700 font-bold rounded-lg">
                        <svg width="24px" height="18px" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M6.30958 3.54424C7.06741 2.56989 8.23263 2 9.46699 2H20.9997C21.8359 2 22.6103 2.37473 23.1614 2.99465C23.709 3.61073 23.9997 4.42358 23.9997 5.25V18.75C23.9997 19.5764 23.709 20.3893 23.1614 21.0054C22.6103 21.6253 21.8359 22 20.9997 22H9.46699C8.23263 22 7.06741 21.4301 6.30958 20.4558L0.687897 13.2279C0.126171 12.5057 0.126169 11.4943 0.687897 10.7721L6.30958 3.54424ZM10.2498 7.04289C10.6403 6.65237 11.2734 6.65237 11.664 7.04289L14.4924 9.87132L17.3208 7.04289C17.7113 6.65237 18.3445 6.65237 18.735 7.04289L19.4421 7.75C19.8327 8.14052 19.8327 8.77369 19.4421 9.16421L16.6137 11.9926L19.4421 14.8211C19.8327 15.2116 19.8327 15.8448 19.4421 16.2353L18.735 16.9424C18.3445 17.3329 17.7113 17.3329 17.3208 16.9424L14.4924 14.114L11.664 16.9424C11.2734 17.3329 10.6403 17.3329 10.2498 16.9424L9.54265 16.2353C9.15212 15.8448 9.15212 15.2116 9.54265 14.8211L12.3711 11.9926L9.54265 9.16421C9.15212 8.77369 9.15212 8.14052 9.54265 7.75L10.2498 7.04289Z"
                                fill="#000000"></path>
                        </svg>
                    </a>
                @endif
            </form>
        </div>
        <!-- Pagination links -->
        {{ $dsExercises->links('vendor.pagination.tailwind') }}
        <div class="flex flex-col mb-8 mt-2 items-cente justify-center">
            <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                <div
                    class="align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200">
                    <table class="min-w-full">
                        <thead>
                            <tr>
                                <th
                                    class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    Numéro</th>
                                {{-- <th
                                    class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    Nom</th> --}}
                                <th
                                    class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    Type</th>
                                <th
                                    class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    Année</th>
                                <th
                                    class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    Académie</th>
                                <th
                                    class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    Date/Text</th>
                                <th
                                    class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    Chapitre duo
                                </th>
                                {{-- <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                        Chapitres associés
                                    </th> --}}
                                <th
                                    class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    Inj
                                </th>
                                <th
                                    class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    Durée
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
                                    {{-- <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                        {{ $ex->name }}</td> --}}
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                        @if ($ex->type === 'bac')
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Bac
                                            </span>
                                        @elseif ($ex->type === 'mimigl')
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                                Mimigl
                                            </span>
                                        @elseif ($ex->type === 'lycee')
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Lycée
                                            </span>
                                        @elseif ($ex->type === 'concours')
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Concours
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                        {{ $ex->year }}</td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                        {{ $ex->academy }}</td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                        {{ $ex->date_data }}</td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                        @foreach ($multipleChapters as $chapter)
                                            @if ($chapter->id === $ex->multiple_chapter_id)
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full truncate"
                                                    style="background-color: {{ $chapter->theme }}; color: black;">
                                                    {{ $chapter->title }}
                                                </span>
                                            @endif
                                        @endforeach
                                    </td>
                                    {{-- <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                            @foreach ($ex->chapters as $chapter)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full truncate  bg-blue-100 text-blue-800">
                                                    {{ $chapter->title }}
                                                </span>
                                            @endforeach
                                        </td> --}}
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                        @if ($ex->harder_exercise)
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Oui
                                            </span>
                                        @else
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Non
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                        {{ $ex->time }} min</td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-right text-sm leading-5 font-medium">
                                        <div class="flex justify-center items-center gap-2">
                                            {{-- Route::get('/ds_exercise/{id}/{filter}', [DsExerciseController::class, 'show'])->name('ds_exercise.show'); --}}
                                            <a
                                                href="{{ route('ds_exercise.show', ['id' => $ex->id, 'filter' => $filterActivated ? 'true' : 'false']) }}">
                                                <svg version="1.1" id="Uploaded to svgrepo.com"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="20px"
                                                    height="20px" viewBox="0 0 32 32" xml:space="preserve"
                                                    fill="#000000">
                                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round"
                                                        stroke-linejoin="round"></g>
                                                    <g id="SVGRepo_iconCarrier">
                                                        <style type="text/css">
                                                            .linesandangles_een {
                                                                fill: #111918;
                                                            }
                                                        </style>
                                                        <path class="linesandangles_een"
                                                            d="M28.895,15.553c-0.131-0.261-2.517-4.919-7.279-7.248c-0.007-0.006-0.012-0.014-0.019-0.02 l-0.006,0.007C19.995,7.514,18.133,7,16,7c-2.133,0-3.996,0.515-5.592,1.291l-0.006-0.006c-0.006,0.006-0.012,0.013-0.018,0.019 c-4.762,2.329-7.148,6.987-7.279,7.248L2.882,16l0.224,0.447C3.28,16.796,7.48,25,16,25s12.72-8.204,12.895-8.553L29.118,16 L28.895,15.553z M20.468,10.005C21.455,11.106,22,12.507,22,14c0,3.309-2.691,6-6,6s-6-2.691-6-6c0-1.493,0.545-2.894,1.532-3.995 C12.836,9.4,14.323,9,16,9C17.678,9,19.164,9.4,20.468,10.005z M16,23c-6.215,0-9.893-5.39-10.853-7 c0.442-0.742,1.468-2.284,3.046-3.733C8.069,12.83,8,13.409,8,14c0,4.411,3.589,8,8,8s8-3.589,8-8c0-0.591-0.069-1.17-0.193-1.733 c1.578,1.449,2.604,2.991,3.046,3.733C25.893,17.61,22.215,23,16,23z M12,14c0-2.209,1.791-4,4-4s4,1.791,4,4c0,2.209-1.791,4-4,4 S12,16.209,12,14z">
                                                        </path>
                                                    </g>
                                                </svg>
                                            </a>
                                            <x-button-edit
                                                href="{{ route('ds_exercise.edit', ['id' => $ex->id, 'filter' => $filterActivated ? 'true' : 'false']) }}" />
                                            <x-button-delete href="{{ route('ds_exercise.destroy', $ex->id) }}"
                                                entity="cet exercice" entityId="ds_exercise{{ $ex->id }}" />
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
