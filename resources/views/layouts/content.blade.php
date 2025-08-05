<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Absensi Kegiatan</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100 font-sans antialiased">

    <div class="flex h-screen">
        @include('layouts.sidebar')

        <div class="flex-1 flex flex-col">
            @include('layouts.header')

            <main class="flex-1 py-4 overflow-auto bg-gray-100">
                @yield('content')
            </main>
        </div>
    </div>

</body>
</html>
