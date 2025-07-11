<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
        <title>@yield('title', config('app.name', 'Maths Manager'))</title>
        <meta name="description" content="@yield('meta_description', 'Plateforme de gestion de maths, exercices, devoirs et plus encore.')" />
        <link rel="canonical" href="@yield('canonical', url()->current())" />

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/scss/app.scss', 'resources/js/app.js'])

    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 mt-5">
            <div>
                <a href="/home">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
            </div>

            <div class="w-11/12 sm:max-w-md m-5 px-6 py-4 shadow-md overflow-hidden sm:rounded-lg card">
                {{ $slot }}
            </div>
        </div>
          
    @include('layouts.footer')
    </body>
</html>
