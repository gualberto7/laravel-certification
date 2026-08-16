<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', config('app.name'))</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    <nav class="bg-slate-900 text-white">
        <div class="mx-auto flex max-w-5xl items-center gap-6 px-6 py-4">
            <a href="{{ route('home') }}" class="font-semibold">
                {{ config('app.name') }}
            </a>

            <a href="{{ route('projects.index') }}">Projects</a>
        </div>
    </nav>

    <main class="mx-auto max-w-5xl px-6 py-8">
        @if (session('status'))
            <div class="mb-6 rounded-lg bg-emerald-100 p-4 text-emerald-800">
                {{ session('status') }}
            </div>
        @endif
        @yield('content')
    </main>
</body>
</html>