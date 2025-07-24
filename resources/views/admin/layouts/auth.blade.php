<!doctype html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Panel Admin</title>

    {{--  desarrollo --}}
    @if (app()->environment('local'))
        @vite('resources/css/app.css')
    @else
        {{-- En producción --}}
        @vite('resources/css/app.css')
    @endif
</head>

<body class="bg-gray-100 text-gray-800 antialiased">
    <div class="min-h-screen">
        <main>
            @yield('content')
        </main>
    </div>

    {{--Vite --}}
    @if (app()->environment('local'))
        @vite('resources/js/app.js')
    @else
        @vite('resources/js/app.js')
    @endif
</body>

</html>
