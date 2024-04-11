@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex justify-between items-center pt-6">
            <div>
                <h2 class="text-lg leading-6 font-medium text-gray-900">DS</h2>
            </div>
            {{-- Add DS button --}}
            <div>
                <a href="{{ route('ds.create') }}"
                    class="px-4 py-2 text-sm text-white bg-green-500 rounded hover:bg-green-600 focus:outline-none">Ajouter
                    un DS</a>
            </div>
        </div>

        {{-- DS list --}}
        <div class="flex flex-col my-8">
            <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                <div
                    class="align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200">
                    <table class="min-w-full">
                        <thead>
                            <tr>
                                <th
                                    class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    User</th>
                                <th
                                    class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    Type Bac</th>
                                <th
                                    class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    Nombre d'exercices</th>
                                <th
                                    class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    Chapitres</th>
                                <th
                                    class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @foreach ($dsList as $ds)
                                <tr>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 font-medium text-gray-900">
                                        {{ $ds->user->name }}</td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                        {{ $ds->type_bac ? 'Oui' : 'Non' }}</td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                        {{ $ds->exercises_number }}</td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                        @foreach ($ds->chapters as $chapter)
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full truncate"
                                                style="background-color: {{ $chapter->theme }}; color: black;">
                                                {{ $chapter->title }}
                                            </span>
                                        @endforeach
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                        {{ $ds->status }}
                                    <td class="px-6 py-4 whitespace-no-wrap text-right text-sm leading-5 font-medium">
                                        <div class="flex justify-center items-center gap-2">
                                            <a href="{{ route('ds.show', $ds->id) }}"
                                                class="text-indigo-600 hover:text-indigo-900">
                                                Voir
                                            </a>
                                            <a href="{{ route('ds.edit', $ds->id) }}"
                                                class="text-indigo-600 hover:text-indigo-900">
                                                Modifier
                                            </a>
                                            <form action="{{ route('ds.destroy', $ds->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce DS ?')"
                                                    class="text-red-600 hover:text-red-900">
                                                    Supprimer
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
