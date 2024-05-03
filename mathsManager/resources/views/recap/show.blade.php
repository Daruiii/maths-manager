@extends('layouts.app')

@section('content')
    <div class="container mx-auto">
        <x-back-btn path="{{ route('classe.show', $recap->chapter->classe->level) }}" />
            <div class="flex flex-col w-full md:w-3/4 bg-[#FBF7F0] p-6 rounded-lg my-8">
                <div class="flex justify-center items-center">
                <h2 class="p-2 text-xs md:text-base font-bold text-center border border-black w-2/3">Fiche récapitulative - {{ $recap->chapter->title }}</h2>
                </div>
            </div>
    </div>
@endsection