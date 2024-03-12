<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Maths Manager</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/scss/app.scss', 'resources/js/app.js'])
    </head>
    <body>
        <div class="container">
            <h1>Maths Manager</h1>
            <h2>Manage your knowledge</h2>
            <p class="code">Maths Manager is a simple web application that allows you to manage your knowledge of mathematics. You can create, read, update, and delete mathematical concepts, and you can also create, read, update, and delete mathematical problems.</p>
        </div>
    </body>
</html>
