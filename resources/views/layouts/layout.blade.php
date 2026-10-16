<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('titulo', 'IT-Secure | Gestión y Monitoreo')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/it-secure-theme.css') }}" />

    @stack('estilos')
</head>
<body class="it-secure-body">
<div class="app-wrapper">

    <nav class="app-header navbar navbar-expand align-items-center">
        <div class="container-fluid px-3">
            <ul class="navbar-nav align-items-center">
                <li class="nav-item">
                    <a class="nav-link toggle-btn" id="menuHamburguesaToggle" href="#" role="button">
                        <i class="bi bi-list fs-4"></i>
                    </a>
                </li>
                <li class="nav-item d-none d-md-block ms-2">
                    <span class="navbar-text text-uppercase fw-semibold tracking-wide dc-brand-sub">
                        Protección & Mantenimiento IT
                    </span>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item me-4 d-none d-sm-flex align-items-center">
                    <span class="status-indicator-live"></span>
                    <span class="status-label">Sistema en línea</span>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" data-bs-toggle="dropdown">
                        <div class="avatar-shield">
                            <i class="bi bi-shield-lock-fill"></i>
                        </div>
                        <span class="d-none d-md-inline text-light fw-medium">{{ \Illuminate\Support\Facades\Auth::user()->nombre }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg dc-dropdown">
                        <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Ajustes</a></li>
                        <li><a class="dropdown-item" href="{{ url('home/acerca') }}"><i class="bi bi-info-circle me-2"></i>Acerca de IT-Secure</a></li>
                        <li><hr class="dropdown-divider border-secondary"></li>
                        <li>
                           <form method="POST" action="{{ route('logout') }}" >
                               @csrf
                               <button type="submit" class="dropdown-item text-danger">
                                   <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
                               </button>
                           </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

    <aside class="app-sidebar" id="appSidebar">
        <div class="sidebar-brand">
            <a href="{{ url('/') }}" class="brand-link">
                <span class="brand-glow-icon"><i class="bi bi-cpu-fill"></i></span>
                <span class="brand-text fw-bold">IT-<span class="text-cyan">SECURE</span></span>
            </a>
        </div>

        <div class="sidebar-wrapper">
            <nav class="mt-4">
                <ul class="nav sidebar-menu flex-column">
                    <li class="nav-header">MONITOREO & INFRAESTRUCTURA</li>
                    <li class="nav-item">
                        <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                            <i class="bi bi-house nav-icon"></i> Inicio
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="bi bi-diagram-3-fill nav-icon"></i> Sedes & Sucursales
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="bi bi-server nav-icon"></i> Racks & Servidores
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('usuarios') }}" class="nav-link {{ request()->routeIs('usuarios*') ? 'active' : '' }}">
                            <i class="bi bi-people nav-icon"></i> Usuarios
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="bi bi-speedometer2 nav-icon"></i> Dashboard
                        </a>
                    </li>

                    <li class="nav-header mt-3">MANTENIMIENTO</li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="bi bi-tools nav-icon"></i> Mantenimiento Preventivo
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="bi bi-exclamation-triangle-fill nav-icon"></i> Incidentes
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

    </aside>

    <main class="app-main">
        <div class="app-content-header py-3">
            <div class="container-fluid px-4">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <h4 class="mb-0 page-title">@yield('subtitulo', 'Estado del Centro de Cómputos')</h4>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end mb-0 dc-breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
                            <li class="breadcrumb-item active" aria-current="page">@yield('breadcrumb', 'Dashboard')</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid px-4 pb-5">
                @yield('contenido')
            </div>
        </div>
    </main>

    <footer class="app-footer">
        <div class="container-fluid px-4 d-flex flex-column flex-sm-row justify-content-between align-items-center">
            <div>
                <strong>&copy; 2026 <span class="text-cyan">IT-Secure</span>.</strong> Todos los derechos reservados.
            </div>
            <div class="mt-2 mt-sm-0">
                <b>Versión</b> 1.0.0 | <a href="{{ url('home/acerca') }}" class="text-cyan text-decoration-none">Acerca de</a>
            </div>
        </div>
    </footer>
</div>
@include('layouts.toast')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/layout-ajax-script.js')}}" ></script>
<script src="{{ asset('js/toast.js') }}"></script>
@stack('scripts')
</body>
</html>
