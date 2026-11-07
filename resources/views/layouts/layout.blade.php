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
<body class="it-secure-body outfit-regular">
<div class="app-wrapper">

    <nav class="app-header navbar navbar-expand align-items-center px-4">
        <div class="container-fluid px-0">
            <ul class="navbar-nav align-items-center gap-3">
                <li class="nav-item">
                    <a class="nav-link toggle-btn p-0" id="menuHamburguesaToggle" href="#" role="button" style="color: var(--dc-text-main); transition: color 0.3s ease;">
                        <i class="bi bi-list fs-4"></i>
                    </a>
                </li>
                <li class="nav-item d-none d-md-block ms-1">
                    <span class="navbar-text text-uppercase outfit-semibold tracking-wide dc-brand-sub mb-0 d-flex align-items-center">
                        <i class="bi bi-shield-check me-2 text-cyan fs-6"></i> Protección & Mantenimiento IT
                    </span>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto align-items-center gap-4">
                <li class="nav-item d-none d-sm-flex align-items-center">
                    <span class="status-indicator-live"></span>
                    <span class="status-label text-uppercase">Sistema en línea</span>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center gap-2 p-0" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="avatar-shield">
                            <i class="bi bi-person-fill fs-5"></i>
                        </div>
                        <span class="d-none d-md-inline text-light outfit-medium" style="font-size: 0.9rem;">
                            {{ \Illuminate\Support\Facades\Auth::user()->nombre ?? 'Administrador' }}
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg dc-dropdown mt-3 border-0">
                        <li>
                            <a class="dropdown-item py-2 d-flex align-items-center" href="{{ route('usuario.perfil') }}">
                                <i class="bi bi-gear me-3 fs-6"></i> Ajustes de Cuenta
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2 d-flex align-items-center" href="{{ url('home/acerca') }}">
                                <i class="bi bi-info-circle me-3 fs-6"></i> Acerca de IT-Secure
                            </a>
                        </li>
                        <li><hr class="dropdown-divider border-secondary opacity-25 my-2"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger py-2 d-flex align-items-center">
                                    <i class="bi bi-box-arrow-right me-3 fs-6"></i> Cerrar Sesión
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
                <span class="brand-text outfit-bold">IT-<span class="text-cyan">SECURE</span></span>
            </a>
        </div>

        <div class="sidebar-wrapper">
            <nav class="mt-2">
                <ul class="nav sidebar-menu flex-column">
                    <li class="nav-header">Monitoreo & Infraestructura</li>

                    <!-- Atributo data-tooltip agregado a cada enlace -->
                    <li class="nav-item">
                        <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" data-tooltip="Inicio">
                            <i class="bi bi-grid-1x2 nav-icon"></i> <span class="nav-text">Inicio</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link" data-tooltip="Sedes & Sucursales">
                            <i class="bi bi-buildings nav-icon"></i> <span class="nav-text">Sedes & Sucursales</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('equipos') }}" class="nav-link {{ request()->routeIs('equipos*') ? 'active' : '' }}" data-tooltip="Equipos">
                            <i class="bi bi-pc-display-horizontal nav-icon"></i> <span class="nav-text">Equipos</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('usuarios') }}" class="nav-link {{ request()->routeIs('usuarios*') ? 'active' : '' }}" data-tooltip="Usuarios">
                            <i class="bi bi-people nav-icon"></i> <span class="nav-text">Usuarios</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" data-tooltip="Dashboard">
                            <i class="bi bi-speedometer2 nav-icon"></i> <span class="nav-text">Dashboard</span>
                        </a>
                    </li>

                    <li class="nav-header mt-3">Mantenimiento y Control</li>
                    <li class="nav-item">
                        <a href="#" class="nav-link" data-tooltip="Mantenimiento Prev.">
                            <i class="bi bi-wrench-adjustable nav-icon"></i> <span class="nav-text">Mantenimiento Prev.</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link" data-tooltip="Incidentes">
                            <i class="bi bi-shield-exclamation nav-icon"></i> <span class="nav-text">Incidentes</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <main class="app-main">
        <div class="app-content-header py-4">
            <div class="container-fluid px-4 px-lg-5">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <h4 class="mb-0 page-title outfit-semibold text-white fs-3">@yield('subtitulo', 'Estado del Centro de Cómputos')</h4>
                    </div>
                    <div class="col-sm-6 mt-3 mt-sm-0">
                        <ol class="breadcrumb float-sm-end mb-0 dc-breadcrumb justify-content-sm-end">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}" class="d-flex align-items-center"><i class="bi bi-house-door me-1"></i> Inicio</a></li>
                            <li class="breadcrumb-item active outfit-medium" aria-current="page">@yield('breadcrumb', 'Dashboard')</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid px-4 px-lg-5 pb-5">
                @yield('contenido')
            </div>
        </div>
    </main>

    <footer class="app-footer d-flex flex-column flex-sm-row justify-content-between align-items-center">
        <div>
            <span class="opacity-75">&copy; {{ date('Y') }}</span> <strong class="outfit-medium text-white ms-1">IT-<span class="text-cyan">Secure</span>.</strong> <span class="opacity-75 ms-1">Todos los derechos reservados.</span>
        </div>
        <div class="mt-2 mt-sm-0 d-flex align-items-center gap-3">
            <span class="badge bg-dark border border-secondary text-secondary outfit-medium px-2 py-1">v 1.0.0</span>
            <a href="{{ url('home/acerca') }}" class="text-cyan text-decoration-none outfit-medium dc-hover-white transition-all">Acerca de la plataforma</a>
        </div>
    </footer>
</div>

@include('layouts.toast')

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/layout-ajax-script.js') }}"></script>
<script src="{{ asset('js/toast.js') }}"></script>


@stack('scripts')
</body>
</html>
