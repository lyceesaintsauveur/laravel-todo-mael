<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title')</title>

        
        @vite(['resources/css/app.css', 'resources/sass/app.scss', 'resources/js/app.js'])
    </head>
    <body>
        <nav class="navbar navbar-expand-md navbar-dark bg-dark dark">
            <a class="navbar-brand" href="/">Ma Todo List</a>
            <a class="navbar-brand btn btn-primary" href="{{ route('todo.liste') }}"><i class="bi bi-app"></i>Liste</a>
            <a class="navbar-brand btn btn-primary" href="{{ route('todos.search') }}">Rechercher</a>
            <a class="navbar-brand btn btn-danger" href="{{ route('todo.compteur') }}">Compteur</a>
            <a class="navbar-brand btn btn-danger" href="{{ route('planning.index') }}">planning</a>
            <a class="navbar-brand btn btn-danger" href="{{ route('profile.edit') }}">Profile Utilisateur</a>
        </nav>

        @yield('content')

    </body>
</html>