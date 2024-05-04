@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-xl font-bold mb-4">Gestion des Classes</h1>
    
    <div class="mb-4">
        {{-- <a href="{{ route('classe.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Ajouter une Classe
        </a> --}}
        <x-button-add href="{{ route('classe.create') }}">Classe</x-button-add>
    </div>

    <div class="bg-white shadow-md rounded mt-3 mb-6">
        <table class="text-left w-full border-collapse">
            <thead>
                <tr>
                    <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">Nom de la Classe</th>
                    <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($classes as $classe)
                    <tr class="hover:bg-grey-lighter">
                        <td class="py-4 px-6 border-b border-grey-light">{{ $classe->name }}</td>
                        <td class="py-4 px-6 border-b border-grey-light flex justify-center align-start gap-2">
                            <x-button-edit href="{{ route('classe.edit', $classe->id) }}"></x-button-edit>
                            <x-button-delete href="{{ route('classe.destroy', $classe->id) }}" entity="cette classe" entityId="classe{{ $classe->id }}"/>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
