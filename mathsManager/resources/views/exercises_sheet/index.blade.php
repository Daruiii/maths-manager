@extends('layouts.app')

@section('content')
    <x-back-btn path="{{ route('admin') }}">Retour</x-back-btn>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- search bar --}}
        <div class="flex justify-between items-center mt-16">
            <x-search-bar-admin action="{{ route('exercises_sheet.index') }}"
                placeholder="Rechercher une fiche d'exercices..." name="search" />
            <h2 class="text-lg leading-6 font-medium text-gray-900">Fiches d'Exercices</h2>
            <a href="{{ route('exercises_sheet.create') }}"
                class="px-4 py-2 text-sm text-white bg-green-500 rounded hover:bg-green-600 focus:outline-none">Créer une
                Fiche d'Exercices</a>
        </div>

        <!-- Pagination links -->
        {{ $exercisesSheetList->links('vendor.pagination.tailwind') }}

        {{-- Exercises Sheets list --}}
        <div class="flex flex-col my-8">
            <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                <div
                    class="align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200">
                    <table class="min-w-full">
                        <thead>
                            <tr>
                                <th
                                    class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    Titre
                                </th>
                                <th
                                    class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    Exercices
                                </th>
                                <th
                                    class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    Chapitres
                                </th>
                                <th
                                    class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th
                                    class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @foreach ($exercisesSheetList as $sheet)
                                <tr>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 font-medium text-gray-900">
                                        {{ $sheet->title }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                        @foreach ($sheet->exercises as $exercise)
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full truncate">
                                                <a href="{{ route('exercise.show', $exercise->id) }}"
                                                    class="text-indigo-600 hover:text-indigo-900">
                                                    #{{ $exercise->id }}
                                                </a>
                                            </span>
                                        @endforeach
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                        {{-- @foreach ($sheet->chapters as $chapter)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full truncate"
                                                  style="background-color: {{ $chapter->theme }}; color: black;">
                                                {{ $chapter->title }}
                                            </span>
                                        @endforeach --}}
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                        {{ $sheet->status }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-right text-sm leading-5 font-medium">
                                        <div class="flex justify-center items-center gap-2">
                                            <x-button-edit href="{{ route('exercises_sheet.edit', $sheet->id) }}" />
                                            <x-button-delete href="{{ route('exercises_sheet.destroy', $sheet->id) }}" entity="cette fiche d'exercices"
                                                entityId="ds{{ $sheet->id }}" />
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
