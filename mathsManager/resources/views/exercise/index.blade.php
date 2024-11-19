@extends('layouts.app')

@section('content')
<x-back-btn path="{{ route('admin') }}"> Retour</x-back-btn>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-center items-center pt-6">
            <div>
                <h2 class="text-lg leading-6 font-medium text-gray-900">Exercices ({{{ $exercises->total() }}})</h2>
            </div>
        </div>
               {{-- Search form --}}
               <div class="flex justify-between items-center py-2">
                {{-- <form method="GET" action="{{ route('exercises.index') }}" class="flex space-x-4">
                    <input type="text" name="search" class="form-input rounded-md shadow-sm mt-1 block w-full"
                        placeholder="Rechercher un exercice...">
                    <button type="submit"
                        class="px-4 py-2 text-sm text-white bg-blue-500 rounded hover:bg-blue-600 focus:outline-none">Rechercher</button>
                </form> --}}
                <x-search-bar-admin action="{{ route('exercises.index') }}" placeholder="Rechercher un exercice..." name="search" />
            </div>
                         <!-- Pagination links -->
                         {{ $exercises->links('vendor.pagination.simple-tailwind') }}
        <div class="flex flex-col mb-8 mt-5">
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
                                        Sous-chapitre associé
                                    </th>
                                <th
                                    class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @foreach ($exercises as $ex)
                                <tr>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 font-medium text-gray-900">
                                        {{ $ex->id }}</td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                        {{ $ex->name }}</td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                            {{ $subchapters->where('id', $ex->subchapter_id)->first()->title }}
                                        </td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-right text-sm leading-5 font-medium">
                                        <div class="flex justify-center items-center">
                                            <a href="{{ route('subchapter.show', $ex->subchapter->id) }}?exercise={{ $ex->id }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                                <svg version="1.1" id="Uploaded to svgrepo.com" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20px" height="20px" viewBox="0 0 32 32" xml:space="preserve" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <style type="text/css"> .linesandangles_een{fill:#111918;} </style> <path class="linesandangles_een" d="M28.895,15.553c-0.131-0.261-2.517-4.919-7.279-7.248c-0.007-0.006-0.012-0.014-0.019-0.02 l-0.006,0.007C19.995,7.514,18.133,7,16,7c-2.133,0-3.996,0.515-5.592,1.291l-0.006-0.006c-0.006,0.006-0.012,0.013-0.018,0.019 c-4.762,2.329-7.148,6.987-7.279,7.248L2.882,16l0.224,0.447C3.28,16.796,7.48,25,16,25s12.72-8.204,12.895-8.553L29.118,16 L28.895,15.553z M20.468,10.005C21.455,11.106,22,12.507,22,14c0,3.309-2.691,6-6,6s-6-2.691-6-6c0-1.493,0.545-2.894,1.532-3.995 C12.836,9.4,14.323,9,16,9C17.678,9,19.164,9.4,20.468,10.005z M16,23c-6.215,0-9.893-5.39-10.853-7 c0.442-0.742,1.468-2.284,3.046-3.733C8.069,12.83,8,13.409,8,14c0,4.411,3.589,8,8,8s8-3.589,8-8c0-0.591-0.069-1.17-0.193-1.733 c1.578,1.449,2.604,2.991,3.046,3.733C25.893,17.61,22.215,23,16,23z M12,14c0-2.209,1.791-4,4-4s4,1.791,4,4c0,2.209-1.791,4-4,4 S12,16.209,12,14z"></path> </g>
                                                </svg>
                                            </a>
                                            <x-button-edit href="{{ route('exercise.edit', $ex->id) }}" />
                                            <x-button-delete href="{{ route('exercise.destroy', $ex->id) }}" entity="cet exercice" entityId="exercise{{ $ex->id }}" />
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
