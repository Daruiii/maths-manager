@extends('layouts.app')

@section('content')
    <x-back-btn path="{{ route('admin') }}"> Retour</x-back-btn>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- search bar --}}
        <div class="flex justify-between items-center mt-16">
            <x-search-bar-admin action="{{ route('ds.index') }}" placeholder="Rechercher un DS..." name="search" />
            <h2 class="text-lg leading-6 font-medium text-gray-900">DS</h2>
            <a href="{{ route('ds.assign') }}"
                class="px-4 py-2 text-sm text-white bg-green-500 rounded hover:bg-green-600 focus:outline-none">Assigner
                un DS</a>
        </div>

        <!-- Pagination links -->
        {{ $dsList->links('vendor.pagination.tailwind') }}
        {{-- DS list --}}
        <div class="flex flex-col my-8">
            <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                <div
                    class="align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200">
                    <table class="min-w-full">
                        <thead>
                            <tr>
                                <th
                                    class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider flex row items-center">
                                    Student
                                    @if ($sort_by_student)
                                        <a href="{{ route('ds.index') }}">
                                            <svg width="15px" height="15px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" transform="rotate(180)"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M8.24499 13.1858L11.111 9.39582C11.2877 9.14748 11.5737 9 11.8785 9C12.1833 9 12.4693 9.14748 12.646 9.39582L15.779 13.1858C16.0355 13.5064 16.0955 13.942 15.9351 14.32C15.7747 14.698 15.4198 14.9575 15.011 14.9958H9.01099C8.60251 14.9569 8.24826 14.6971 8.08834 14.3192C7.92841 13.9413 7.98856 13.5062 8.24499 13.1858Z" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                                        </a>
                                    @else
                                        <a href="{{ route('ds.index', ['sort_by_student' => 'true']) }}">
                                            <svg width="15px" height="15px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M8.24499 13.1858L11.111 9.39582C11.2877 9.14748 11.5737 9 11.8785 9C12.1833 9 12.4693 9.14748 12.646 9.39582L15.779 13.1858C16.0355 13.5064 16.0955 13.942 15.9351 14.32C15.7747 14.698 15.4198 14.9575 15.011 14.9958H9.01099C8.60251 14.9569 8.24826 14.6971 8.08834 14.3192C7.92841 13.9413 7.98856 13.5062 8.24499 13.1858Z" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                                        </a>
                                    @endif
                                </th>
                                <th
                                    class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    exercices</th>
                                <th
                                    class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    Note
                                </th>
                                <th
                                    class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    Chapitres</th>
                                <th
                                    class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider flex row items-center"">
                                    Status
                                    @if ($sort_by_status)
                                        <a href="{{ route('ds.index') }}">
                                            <svg width="15px" height="15px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" transform="rotate(180)"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M8.24499 13.1858L11.111 9.39582C11.2877 9.14748 11.5737 9 11.8785 9C12.1833 9 12.4693 9.14748 12.646 9.39582L15.779 13.1858C16.0355 13.5064 16.0955 13.942 15.9351 14.32C15.7747 14.698 15.4198 14.9575 15.011 14.9958H9.01099C8.60251 14.9569 8.24826 14.6971 8.08834 14.3192C7.92841 13.9413 7.98856 13.5062 8.24499 13.1858Z" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                                        </a>
                                    @else
                                        <a href="{{ route('ds.index', ['sort_by_status' => 'true']) }}">
                                            <svg width="15px" height="15px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M8.24499 13.1858L11.111 9.39582C11.2877 9.14748 11.5737 9 11.8785 9C12.1833 9 12.4693 9.14748 12.646 9.39582L15.779 13.1858C16.0355 13.5064 16.0955 13.942 15.9351 14.32C15.7747 14.698 15.4198 14.9575 15.011 14.9958H9.01099C8.60251 14.9569 8.24826 14.6971 8.08834 14.3192C7.92841 13.9413 7.98856 13.5062 8.24499 13.1858Z" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                                        </a>
                                    @endif
                                </th>
                                <th
                                    class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @foreach ($dsList as $ds)
                                <tr>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 font-medium text-gray-900">
                                        {{ $ds->user->name ?? 'Utilisateur supprimé' }}</td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                        @foreach ($ds->exercisesDS as $exercise)
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full truncate">
                                                <a href="{{ route('ds_exercise.show', ['id' => $exercise->id, 'filter' => 'false']) }}"
                                                    class="text-indigo-600 hover:text-indigo-900">
                                                #{{ $exercise->id }}
                                                </a>
                                            </span>
                                        @endforeach
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                        @if ($ds->correctionRequest)
                                            {{ $ds->correctionRequest->grade }}/20
                                        @else
                                            Non corrigé
                                        @endif
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                        @if ($ds->type_bac)
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full truncate bg-green-100 text-green-800">
                                                Type Bac
                                            </span>
                                        @else
                                            @foreach ($ds->multipleChapters as $chapter)
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full truncate"
                                                    style="background-color: {{ $chapter->theme }}; color: black;">
                                                    {{ $chapter->title }}
                                                </span>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                        {{ $ds->status }}
                                    <td class="px-6 py-4 whitespace-no-wrap text-right text-sm leading-5 font-medium">
                                        <div class="flex justify-center items-center gap-2">
                                            <a href="{{ route('ds.reAssignForm', $ds->id) }}" class="text-indigo-600 hover:text-indigo-900">Réaffecter</a>
                                            <a href="{{ route('ds.show', $ds->id) }}"
                                                class="text-indigo-600 hover:text-indigo-900">
                                                <svg version="1.1" id="Uploaded to svgrepo.com"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="20px" height="20px"
                                                    viewBox="0 0 32 32" xml:space="preserve" fill="#000000">
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
                                            <x-button-edit href="{{ route('ds.edit', $ds->id) }}" />
                                            <x-button-delete href="{{ route('ds.destroy', $ds->id) }}" entity="ce DS"
                                                entityId="ds{{ $ds->id }}" />
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
