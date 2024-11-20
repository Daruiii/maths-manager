@extends('layouts.app')

@section('content')
@php
// dd($quizzes, $user,$ds);
@endphp
  {{--  display the user's name  --}}
  <h2 class="text-2xl font-bold text-center mb-4">{{ $user->name }}</h2>
  {{--  display the user's email  --}}
@endsection