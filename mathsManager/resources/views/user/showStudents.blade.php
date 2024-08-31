@extends('layouts.app')

@section('content')
    <div class="container mx-auto mb-8">
        <div class="flex justify-start flex-col items-start w-9/12 mt-6 mb-4">
            <h1 class="text-xl mb-2">Liste des élèves</h1>
            <div class="flex justify-between items-center py-3">
                <x-search-bar-admin action="{{ route('students.show') }}" placeholder="Rechercher un utilisateur..."
                    name="search" />
            </div>
            <div class="flex row justify-center items-center flex-wrap gap-5 w-full mb-5">
                @foreach ($students as $student)
                    <div class="bg-white rounded-lg p-2 w-84 min-w-80 shadow-md transition duration-300 mb-4 hover:shadow-lg flex flex-col items-center">
                        <div class="w-full flex flex-row justify-start items-center border-b border-gray-200 p-2 mb-2">
                        @if (Str::startsWith($student->avatar, 'http'))
                            <img src="{{ $student->avatar }}"
                                class="w-12 h-12 rounded-full border border-black object-cover hover:brightness-50 transition duration-300  "alt="Profile Picture">
                        @else
                            <img src="{{ asset('storage/images/' . $student->avatar) }}"
                                class="w-12 h-12 rounded-full border border-black object-cover hover:brightness-50 transition duration-300"
                                alt="Profile Picture">
                        @endif
                        <div class="w-full flex flex-col justify-center items-center">
                        <h2>{{ $student->name }}</h2>
                        <p class="text-xs"> {{ $student->email }}</p>
                        </div>
                        </div>
                        <div class="w-full gap-2 flex flex-col justify-center items-center">
                            <x-button-generate href="{{ route('ds.assign', ['student_id' => $student->id]) }}">
                                {{ __('Assigner un DS') }}
                            </x-button-generate>
                            <x-button-generate href="{{ route('exercises_sheet.selectChapter', ['student_id' => $student->id]) }}">
                                {{ __("Assigner une fiche d'exercices") }}
                            </x-button-generate>
                            <x-button-quizz href="{{ route('student.quizzes', ['student_id' => $student->id]) }}">
                                {{ __('Voir les quizz') }}
                            </x-button-quizz>
                            @php
                                $last_ds = new DateTime($student->last_ds_generated_at);
                            @endphp
                            <div class="flex flex-row justify-between w-full items-center">
                            @if ($student->last_ds_generated_at == null || date('Y-m-d') != $last_ds->format('Y-m-d'))
                                <button type="submit"
                                    class="text-white bg-gray-500 rounded-full px-2 py-1 cursor-not-allowed">
                                    DS+
                                </button>
                            @else
                                <form action="{{ route('user.resetLastDSGeneratedAt', $student->id) }}" method="POST"
                                    class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-white bg-blue-500 hover:bg-blue-700 rounded-full px-2 py-1">DS+</button>
                                </form>
                            @endif
                            @if($student->verified)
                                <form action="{{ route('user.unverify', $student->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-xs text-red-600 hover:text-red-900 p-2 border border-red-600 rounded-full bg-red-100">Désactiver</button>
                                </form>
                            @else
                                <form action="{{ route('user.verify', $student->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-xs text-green-600 hover:text-green-900 p-2 border border-green-600 rounded-full bg-green-100">Activer</button>
                                </form>
                            @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        {{ $students->links() }}
    </div>
@endsection