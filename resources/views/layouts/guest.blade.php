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
<body class="it-secure-body d-flex flex-column min-vh-100">

<nav class="navbar border-bottom py-3" style="background: rgba(12, 18, 32, 0.85); border-color: rgba(56, 189, 248, 0.12) !important;">
    <div class="container d-flex justify-content-center">
        <a href="{{ url('/') }}" class="text-decoration-none d-flex align-items-center gap-2">
                <span class="brand-glow-icon">
                    <i class="bi bi-cpu-fill"></i>
                </span>
            <span class="fs-4 text-white fw-bold">IT-<span class="text-cyan">SECURE</span></span>
        </a>
    </div>
</nav>

<main class="flex-grow-1 d-flex align-items-center justify-content-center p-3">
    @yield('contenido')
</main>

<footer class="py-3 text-center border-top" style="background-color: var(--dc-bg-surface); border-color: rgba(56, 189, 248, 0.12) !important;">
    <div class="container text-muted" style="font-size: 0.85rem;">
        <strong>&copy; {{ date('Y') }} <span class="text-cyan">IT-Secure</span>.</strong> Acceso Restringido.
    </div>
</footer>

@include('layouts.toast')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/layout-ajax-script.js')}}" ></script>
<script src="{{ asset('js/toast.js') }}"></script>
@stack('scripts')
</body>
</html>
