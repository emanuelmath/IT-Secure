<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('titulo', 'Bienvenido | IT-Secure')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <link rel="stylesheet" href="{{ asset('css/it-secure-theme.css') }}" />

    @stack('estilos')
</head>
<body class="it-secure-body d-flex flex-column min-vh-100 outfit-regular">

<nav class="navbar py-3 dc-guest-nav">
    <div class="container d-flex justify-content-center">
        <a href="{{ url('/') }}" class="text-decoration-none d-flex align-items-center gap-3 dc-hover-lift-link">
            <span class="brand-glow-icon dc-icon-lg">
                <i class="bi bi-cpu-fill"></i>
            </span>
            <span class="fs-4 text-white outfit-bold tracking-wide">IT-<span class="text-cyan">SECURE</span></span>
        </a>
    </div>
</nav>

<main class="flex-grow-1 d-flex align-items-center justify-content-center p-3 dc-guest-main">
    @yield('contenido')
</main>

<footer class="py-4 text-center dc-guest-footer">
    <div class="container text-muted outfit-medium dc-text-sm">
        <span class="opacity-75">&copy; {{ date('Y') }}</span> <strong class="text-white ms-1">IT-<span class="text-cyan">Secure</span>.</strong> <span class="opacity-75 ms-1">Acceso Restringido.</span>
    </div>
</footer>

@include('layouts.toast')

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/toast.js') }}"></script>
<script src="{{ asset('js/it-secure-app-ajax-script.js') }}"></script>
@stack('scripts')
</body>
</html>
