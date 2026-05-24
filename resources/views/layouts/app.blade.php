<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Star Jasmani')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link rel="icon" href="{{ asset('pict/logo-removebg.png') }}" />
    @stack('styles')
</head>
<body class="bg-black">

    @if(session('success'))
        <div id="flash-success" class="fixed top-4 right-4 z-50 bg-green-900 border border-green-700 text-green-300 px-5 py-3 rounded-xl shadow-2xl text-sm font-medium flex items-center gap-3 max-w-sm">
            <i class="fa-solid fa-circle-check flex-shrink-0"></i>
            <span>{{ session('success') }}</span>
        </div>
        <script>setTimeout(() => document.getElementById('flash-success')?.remove(), 4000)</script>
    @endif

    @if(session('error'))
        <div id="flash-error" class="fixed top-4 right-4 z-50 bg-red-900 border border-red-700 text-red-300 px-5 py-3 rounded-xl shadow-2xl text-sm font-medium flex items-center gap-3 max-w-sm">
            <i class="fa-solid fa-circle-exclamation flex-shrink-0"></i>
            <span>{{ session('error') }}</span>
        </div>
        <script>setTimeout(() => document.getElementById('flash-error')?.remove(), 5000)</script>
    @endif

    @auth
        @include('layouts.partials.sidebar')
        @include('layouts.partials.mobile-nav')
    @endauth

    <main class="{{ auth()->check() ? 'lg:ml-64 pb-24 lg:pb-0' : '' }} min-h-screen">
        <div class="p-0">
            @yield('content')
        </div>
    </main>

    @stack('scripts')
</body>
</html>