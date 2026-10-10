<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Clínica FIMOVE') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col justify-center items-center bg-gray-100 px-4">

            <div class="w-full max-w-sm bg-white shadow-md rounded-xl px-8 py-10">
                <div class="flex flex-col items-center mb-6">
                    <img src="{{ asset('images/logo.jpeg') }}" alt="Clínica FIMOVE"
                         class="w-16 h-16 rounded-full object-cover mb-3">
                    <h1 class="text-lg font-semibold text-gray-800">Clínica FIMOVE</h1>
                    <p class="text-sm text-gray-500 text-center mt-1">
                        Sistema de gestión administrativa y control de pacientes
                    </p>
                </div>

                {{ $slot }}
            </div>

        </div>
    </body>
</html>