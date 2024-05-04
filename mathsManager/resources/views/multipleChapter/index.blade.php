@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-xl font-bold mb-4">Gestion des Chapitres</h1>
    
    <div class="mb-4">
        {{-- <a href="{{ route('multiple_chapter.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Ajouter un Chapitre
        </a> --}}
        <x-button-add href="{{ route('multiple_chapter.create') }}">Chapitre</x-button-add>
    </div>

    <div class="bg-white shadow-md rounded my-6">
        <table class="text-left w-full border-collapse">
            <thead>
                <tr>
                    <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">Numéro de Chapitre</th>
                    <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">Titre du Chapitre</th>
                    <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($multipleChapters as $indexChap => $multipleChapters)
                    <tr class="hover:bg-grey-lighter">
                        <td class="py-4 px-6 border-b border-grey-light">{{ $multipleChapters->id }}</td>
                        <td class="py-4 px-6 border-b border-grey-light">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full truncate" style="background-color: {{ $multipleChapters->theme }}; color: black;">
                                {{ $multipleChapters->title }}
                            </span>
                        </td>
                        <td class="py-4 px-6 border-b border-grey-light flex justify-center align-start gap-2">
                            <x-button-edit href="{{ route('multiple_chapter.edit', $multipleChapters->id) }}" />
                            <x-button-delete href="{{ route('multiple_chapter.destroy', $multipleChapters->id) }}" entity="ce chapitre" entityId="duo_chapter_{{ $multipleChapters->id }}" />
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
