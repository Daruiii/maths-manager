@extends('layouts.app')

@section('content')
<x-back-btn path="{{ route('admin') }}"> Retour</x-back-btn>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-center items-center pt-6">
            <div>
                <h2 class="text-lg leading-6 font-medium text-gray-900">Utilisateurs ({{ $users->total() }})</h2>
            </div>
            <div>
                <x-button-add href="{{ route('user.create') }}">Utilisateur</x-button-add>
            </div>
        </div>
        {{-- Search form --}}
        <div class="flex justify-between items-center py-3">
            <x-search-bar-admin action="{{ route('users.index') }}" placeholder="Rechercher un utilisateur..."
                name="search" />
        </div>
        {{-- Pagination links --}}
        {{ $users->links('vendor.pagination.tailwind') }}
        <div class="flex flex-col mb-8 mt-5">
            <div class="my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                <div
                    class="align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200">
                    <table class="min-w-full">
                        <thead>
                            <tr>
                                <th
                                    class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    Nom</th>
                                <th
                                    class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    Email</th>
                                <th
                                    class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    Rôle
                                </th>
                                <th
                                    class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @foreach ($users as $user)
                                <tr>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 font-medium text-gray-900">
                                        <div class="flex items-center gap-1">
                                            @if (Str::startsWith($user->avatar, 'http'))
                                                <img src="{{ $user->avatar }}"
                                                    class="w-9 h-9 rounded-full border border-black object-cover hover:brightness-50 transition duration-300  "alt="Profile Picture">
                                            @else
                                                <img src="{{ asset('storage/images/' . $user->avatar) }}"
                                                    class="w-9 h-9 rounded-full border border-black object-cover hover:brightness-50 transition duration-300"
                                                    alt="Profile Picture">
                                            @endif
                                            {{ $user->name }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                        {{ $user->email }}</td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                        {{ $user->role }} {{-- Assurez-vous que cette propriété existe sur votre modèle User --}}
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-right text-sm leading-5 font-medium">
                                        <div class="gap-2 flex justify-center items-center">
                                            {{-- user.resetLastDSGeneratedAt --}}
                                            @php
                                                $last_ds = new DateTime($user->last_ds_generated_at);
                                            @endphp
                                            @if ($user->last_ds_generated_at == null || date('Y-m-d') != $last_ds->format('Y-m-d'))
                                                <button type="submit"
                                                    class="text-white bg-gray-500 rounded-full px-2 py-1 cursor-not-allowed">
                                                    DS+
                                                </button>
                                            @else
                                                {{-- disabled button --}}
                                                <form action="{{ route('user.resetLastDSGeneratedAt', $user->id) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                        class="text-white bg-blue-500 hover:bg-blue-700 rounded-full px-2 py-1">
                                                        DS+
                                                    </button>
                                                </form>
                                            @endif
                                            {{-- if user->verified set to false, if !user->verified set to true --}}
                                            @if ($user->verified)
                                                <form action="{{ route('user.unverify', $user->id) }}" method="POST"
                                                    class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                                        Désactiver
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('user.verify', $user->id) }}" method="POST"
                                                    class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                        class="text-green-600 hover:text-green-900">Activer</button>
                                                </form>
                                            @endif
                                            <x-button-edit href="{{ route('user.edit', $user->id) }}" />
                                            <x-button-delete href="{{ route('user.destroy', $user->id) }}" entity="cet utilisateur" entityId="user{{ $user->id }}" />
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
