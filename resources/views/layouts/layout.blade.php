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
                    <a class="nav-link toggle-btn p-0" id="menuHamburguesaToggle" href="#" role="button">
                        <i class="bi bi-list fs-4"></i>
                    </a>
                </li>
                <li class="nav-item d-none d-md-block ms-1">
                    <span class="navbar-text text-uppercase outfit-semibold tracking-wide dc-brand-sub mb-0 d-flex align-items-center">
                        <i class="bi bi-shield-check me-2 text-cyan fs-6"></i> Protección & Mantenimiento IT
                    </span>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto align-items-center gap-3 gap-lg-4">
                @if(auth()->check() && auth()->user()->tieneRol('Administrador'))
                    @php
                        $empresaActivaId = session('empresa_activa_id');
                        $empresasContexto = \App\Models\Empresa::mostrarTodos();
                        $empresaSeleccionada = $empresaActivaId ? $empresasContexto->firstWhere('id', $empresaActivaId) : null;
                    @endphp
                    <li class="nav-item dropdown d-none d-sm-block">
                        <button class="btn btn-sm dc-btn-outline d-flex align-items-center gap-2 rounded-pill px-3 py-1" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Cambiar contexto de empresa activa">
                            <i class="bi bi-building text-cyan fs-6"></i>
                            <span class="outfit-medium text-light text-truncate dc-context-truncate">
                                {{ $empresaSeleccionada ? $empresaSeleccionada->nombre : 'Todas las Empresas' }}
                            </span>
                            <i class="bi bi-chevron-down text-muted dc-text-micro ms-1"></i>
                        </button>

                        <form action="{{ route('contexto.empresa') }}" method="POST" id="formContextoEmpresa">
                            @csrf
                            <ul class="dropdown-menu dropdown-menu-end shadow-lg dc-dropdown dc-dropdown-scroll mt-2 border-0">
                                <li class="dropdown-header text-uppercase dc-text-micro tracking-wide text-cyan outfit-semibold px-3 py-2">
                                    <i class="bi bi-filter me-1"></i> Contexto Organizacional
                                </li>
                                <li>
                                    <button type="submit" name="empresa_id" value="" class="dropdown-item d-flex align-items-center justify-content-between py-2 {{ !$empresaActivaId ? 'active text-cyan fw-bold' : '' }}">
                                        <span class="d-flex align-items-center gap-2">
                                            <i class="bi bi-globe2 text-muted"></i> Todas las Empresas (Global)
                                        </span>
                                        @if(!$empresaActivaId)
                                            <i class="bi bi-check2 text-cyan fs-5"></i>
                                        @endif
                                    </button>
                                </li>
                                @if($empresasContexto->isNotEmpty())
                                    <li><hr class="dropdown-divider border-secondary opacity-25 my-1"></li>
                                    @foreach($empresasContexto as $empContext)
                                        @php $esActiva = ($empresaActivaId == $empContext->id); @endphp
                                        <li>
                                            <button type="submit" name="empresa_id" value="{{ $empContext->id }}" class="dropdown-item d-flex align-items-center justify-content-between py-2 {{ $esActiva ? 'active text-cyan fw-bold' : '' }}">
                                                <span class="d-flex align-items-center gap-2 text-truncate dc-item-truncate" title="{{ $empContext->nombre }}">
                                                    <i class="bi bi-building text-muted"></i> {{ $empContext->nombre }}
                                                </span>
                                                @if($esActiva)
                                                    <i class="bi bi-check2 text-cyan fs-5"></i>
                                                @endif
                                            </button>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </form>
                    </li>
                @endif

                <li class="nav-item d-none d-sm-flex align-items-center">
                    <span class="status-indicator-live"></span>
                    <span class="status-label text-uppercase">Sistema en línea</span>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center gap-2 p-0" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="avatar-shield">
                            <i class="bi bi-person-fill fs-5"></i>
                        </div>
                        <span class="d-none d-md-inline text-light outfit-medium">
                            {{ \Illuminate\Support\Facades\Auth::user()->nombre ?? 'Usuario' }}
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

                    <li class="nav-item">
                        <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" data-tooltip="Inicio">
                            <i class="bi bi-grid-1x2 nav-icon"></i> <span class="nav-text">Inicio</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" data-tooltip="Dashboard">
                            <i class="bi bi-speedometer2 nav-icon"></i> <span class="nav-text">Dashboard</span>
                        </a>
                    </li>

                    @if(auth()->check() && auth()->user()->tieneRol('Administrador'))
                        <li class="nav-item">
                            <a href="{{ route('empresas') }}" class="nav-link {{ request()->routeIs('empresas*') ? 'active' : '' }}" data-tooltip="Empresas">
                                <i class="bi bi-building nav-icon"></i> <span class="nav-text">Empresas</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('sedes') }}" class="nav-link {{ request()->routeIs('sedes*') ? 'active' : '' }}" data-tooltip="Sedes & Sucursales">
                                <i class="bi bi-buildings nav-icon"></i> <span class="nav-text">Sedes & Sucursales</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('centros') }}" class="nav-link {{ request()->routeIs('centros*') ? 'active' : '' }}" data-tooltip="Centros de Cómputo">
                                <i class="bi bi-hdd-network nav-icon"></i> <span class="nav-text">Centros de Cómputo</span>
                            </a>
                        </li>
                    @endif

                    @if(auth()->check() && (auth()->user()->tieneRol('Administrador') || auth()->user()->tieneRol('Supervisor')))
                        <li class="nav-item">
                            <a href="{{ route('equipos') }}" class="nav-link {{ request()->routeIs('equipos*') ? 'active' : '' }}" data-tooltip="Equipos">
                                <i class="bi bi-pc-display-horizontal nav-icon"></i> <span class="nav-text">Equipos</span>
                            </a>
                        </li>
                    @endif

                    @if(auth()->check() && auth()->user()->tieneRol('Administrador'))
                        <li class="nav-item">
                            <a href="{{ route('usuarios') }}" class="nav-link {{ request()->routeIs('usuarios*') ? 'active' : '' }}" data-tooltip="Usuarios">
                                <i class="bi bi-people nav-icon"></i> <span class="nav-text">Usuarios</span>
                            </a>
                        </li>
                    @endif

                    <li class="nav-header mt-3">Mantenimiento y Control</li>
                    <li class="nav-item">
                        <a href="{{ route('mantenimientos') }}" class="nav-link {{ request()->routeIs('mantenimientos*') ? 'active' : '' }}" data-tooltip="Mantenimientos">
                            <i class="bi bi-wrench-adjustable nav-icon"></i> <span class="nav-text">Mantenimientos</span>
                        </a>
                    </li>

                    @if(auth()->check() && (auth()->user()->tieneRol('Administrador') || auth()->user()->tieneRol('Supervisor')))
                        <li class="nav-item">
                            <a href="{{ route('traslados') }}" class="nav-link {{ request()->routeIs('traslados*') ? 'active' : '' }}" data-tooltip="Traslados">
                                <i class="bi bi-arrow-left-right nav-icon"></i> <span class="nav-text">Traslados</span>
                            </a>
                        </li>
                    @endif

                    @if(auth()->check() && auth()->user()->tieneRol('Administrador'))
                        <li class="nav-item">
                            <a href="{{ route('bitacoras') }}" class="nav-link {{ request()->routeIs('bitacoras*') ? 'active' : '' }}" data-tooltip="Bitácoras">
                                <i class="bi bi-journal-code nav-icon"></i> <span class="nav-text">Bitácoras</span>
                            </a>
                        </li>
                    @endif
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
<script src="{{ asset('js/it-secure-app-ajax-script.js') }}"></script>
@stack('scripts')
</body>
</html>
