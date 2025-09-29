@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
<x-back-btn path="{{ route('admin') }}"> Retour</x-back-btn>
    <h1 class="text-xl font-bold mb-4">Gestion des Classes</h1>
    
    <div class="mb-4 flex items-center space-x-4">
        <x-button-add href="{{ route('classe.create') }}">Classe</x-button-add>
        <button id="reorder-classes-button"
            class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700 focus:outline-none"
            data-original-text="Réorganiser les classes">
            Réorganiser les classes
        </button>
    </div>

    <div class="bg-white shadow-md rounded mt-3 mb-6">
        <table class="text-left w-full border-collapse">
            <thead>
                <tr>
                    <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">Ordre</th>
                    <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">Nom de la Classe</th>
                    <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">Actions</th>
                </tr>
            </thead>
            <tbody id="classes-container">
                @foreach ($classes as $classe)
                    <tr class="hover:bg-grey-lighter class-row" id="class-{{ $classe->id }}" data-display-order="{{ $classe->display_order }}">
                        <td class="py-4 px-6 border-b border-grey-light">
                            <div class="flex items-center">
                                <div class="drag-handle-class hidden mr-2 cursor-move text-gray-500 hover:text-gray-700">☰</div>
                                <span class="font-semibold">{{ $classe->display_order }}</span>
                            </div>
                        </td>
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

{{-- JavaScript pour drag-and-drop des classes --}}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script src="{{ asset('js/multi-level-drag-drop.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('🚀 Drag & Drop des classes initialisé');
        
        // Configuration pour le drag & drop des classes
        window.MultiLevelDragDrop.initLevel({
            containerId: 'classes-container',
            handleClass: 'drag-handle-class',
            buttonId: 'reorder-classes-button',
            itemClass: 'class-row',
            reorderRoute: '{{ route('ordering.reorderClasses') }}',
            level: 'class',
        });
        
        console.log('✅ Configuration des classes terminée');
    });
</script>
@endsection
