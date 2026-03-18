<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle ?? 'Dashboard' }} — XStock</title>

    <!-- Fuente -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet">

    <!-- CSS principal (sin necesidad de Vite) -->
    <link rel="stylesheet" href="{{ asset('css/xstock.css') }}">

    {{-- Vite sólo si está disponible (dev server activo) --}}
    @php
        $manifest = public_path('build/manifest.json');
    @endphp
    @if(file_exists($manifest))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="{{ asset('js/app.js') }}" defer></script>
    @endif
</head>
<body>

<div class="layout-wrapper">

    @include('layouts.sidebar')

    <div class="main-content">

        {{-- TOPBAR --}}
        <div class="topbar">
            <span class="topbar-title">{{ $pageTitle ?? 'Dashboard' }}</span>
            <div class="topbar-actions">
                <span style="font-size:13px; color:var(--color-text-muted)">
                    {{ Auth::user()->name ?? 'Usuario' }}
                </span>
                <form method="POST" action="{{ route('logout') }}" style="margin:0">
                    @csrf
                    <button type="submit" class="btn btn-secondary btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                        Salir
                    </button>
                </form>
            </div>
        </div>

        {{-- CONTENIDO --}}
        <div class="page-content">
            {{ $slot }}
        </div>

    </div>
</div>

</body>
</html>
