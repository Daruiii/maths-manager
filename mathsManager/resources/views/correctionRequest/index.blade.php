@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex justify-between items-center pt-6">
            <div>
                <h2 class="text-lg leading-6 font-medium text-gray-900">Demandes de correction</h2>
            </div>
          
        </div>
        {{-- search bar --}}
        <div class="flex justify-between items-center mt-6">
            <form method="GET" action="{{ route('correctionRequest.index') }}" class="flex space-x-4">
                <input type="text" name="search" class="form-input rounded-md shadow-sm mt-1 block w-full"
                    placeholder="Rechercher une demande de correction...">
                <button type="submit"
                    class="px-4 py-2 text-sm text-white bg-blue-500 rounded hover:bg-blue-600 focus:outline-none">Rechercher</button>
            </form>
        </div>

        {{-- Correction request list --}}
        <div class="flex flex-col my-8">
            <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                <div
                    class="align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200">
                    <table class="min-w-full">
                        <thead>
                            <tr>
                                <th
                                    class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    Utilisateur</th>
                                <th
                                    class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    Statut</th>
                                {{-- ds id --}}
                                <th
                                    class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    DS
                                </th>
                                <th
                                    class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @foreach ($correctionRequests as $correctionRequest)
                                <tr>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 font-medium text-gray-900">
                                        {{ $correctionRequest->user->name ?? 'Utilisateur supprimé' }}</td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                        {{ $correctionRequest->status }}</td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                        <a href="{{ route('ds.show', $correctionRequest->ds_id) }}"
                                            class="text-indigo-600 hover:text-indigo-900 underline">
                                            DS n°{{ $correctionRequest->ds_id }}
                                        </a>
                                    <td class="px-6 py-4 whitespace-no-wrap text-right text-sm leading-5 font-medium">
                                        <div class="flex justify-center items-center gap-2">
                                            <a href="{{ route('correctionRequest.show', $correctionRequest->ds_id) }}"
                                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                                Voir
                                            </a>
                                            {{-- <a href="{{ route('correctionRequest.correctForm', $correctionRequest->ds_id) }}"
                                                class="text-indigo-600 hover:text-indigo-900">
                                                Modifier la demande
                                            </a> --}}
                                            <x-button-delete href="{{ route('correctionRequest.destroy', $correctionRequest->ds_id) }}" entity="cette demande de correction" entityId="correctionRequest{{ $correctionRequest->ds_id }}" />
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
