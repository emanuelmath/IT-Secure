@extends('layouts.layout')

@section('titulo', 'Inicio | IT-Secure')
@section('subtitulo', 'Panel Principal')
@section('breadcrumb', 'Inicio')

@push('estilos')
    <link rel="stylesheet" href="{{ asset('css/home-theme.css') }}">
@endpush

@section('contenido')
    <div class="dc-card mt-2 mb-5 text-center py-5 home-hero-card">
        <div class="card-body">
            <div class="avatar-shield mx-auto mb-4 d-flex align-items-center justify-content-center home-hero-avatar">
                <i class="bi bi-person-badge text-cyan"></i>
            </div>
            <h1 class="h2 text-light outfit-bold mb-2 tracking-wide">
                ¡Bienvenido de nuevo, <span class="text-cyan">{{ \Illuminate\Support\Facades\Auth::user()->nombre ?? 'Usuario' }}</span>!
            </h1>
            <p class="text-muted fs-6 mb-0 outfit-medium">Sistema de Gestión y Monitoreo de Infraestructura IT</p>
        </div>
    </div>

    <div class="mb-5">
        <div class="row g-4">
            @if(auth()->check() && (auth()->user()->tieneRol('Administrador') || auth()->user()->tieneRol('Supervisor')))
                <div class="col-12 col-md-6">
                    <a href="{{ route('equipos') }}" class="text-decoration-none dc-hover-lift-link">
                        <div class="home-quick-link-card border-cyan-link">
                            <div class="home-icon-circle icon-cyan">
                                <i class="bi bi-pc-display"></i>
                            </div>
                            <div>
                                <h5 class="text-light outfit-semibold mb-1">Inventario de Equipos</h5>
                                <small class="text-muted outfit-regular">Gestionar hardware y fichas técnicas</small>
                            </div>
                        </div>
                    </a>
                </div>
            @endif

            <div class="col-12 col-md-6">
                <a href="{{ route('dashboard') }}" class="text-decoration-none dc-hover-lift-link">
                    <div class="home-quick-link-card border-orange-link">
                        <div class="home-icon-circle icon-orange">
                            <i class="bi bi-speedometer2"></i>
                        </div>
                        <div>
                            <h5 class="text-light outfit-semibold mb-1">Dashboard NOC</h5>
                            <small class="text-muted outfit-regular">Métricas y mantenimientos pendientes</small>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-12 col-md-6">
                <a href="{{ route('mantenimientos') }}" class="text-decoration-none dc-hover-lift-link">
                    <div class="home-quick-link-card border-green-link">
                        <div class="home-icon-circle icon-green">
                            <i class="bi bi-wrench-adjustable"></i>
                        </div>
                        <div>
                            <h5 class="text-light outfit-semibold mb-1">Mantenimientos</h5>
                            <small class="text-muted outfit-regular">Bitácora preventiva y correctiva</small>
                        </div>
                    </div>
                </a>
            </div>

            @if(auth()->check() && auth()->user()->tieneRol('Administrador'))
                <div class="col-12 col-md-6">
                    <a href="{{ route('sedes') }}" class="text-decoration-none dc-hover-lift-link">
                        <div class="home-quick-link-card border-blue-link">
                            <div class="home-icon-circle icon-blue">
                                <i class="bi bi-diagram-3-fill"></i>
                            </div>
                            <div>
                                <h5 class="text-light outfit-semibold mb-1">Sedes y Sucursales</h5>
                                <small class="text-muted outfit-regular">Estructura organizacional</small>
                            </div>
                        </div>
                    </a>
                </div>
            @endif
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <h5 class="text-light mb-4 outfit-semibold tracking-wide d-flex align-items-center">
                <i class="bi bi-bell-fill text-cyan me-2 opacity-75"></i> Avisos del Sistema
            </h5>
            <div class="dc-card">
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush dc-list-group">
                        @if(($totalVencidos ?? 0) > 0)
                            <li class="list-group-item d-flex align-items-start py-4 px-4 transition-all">
                                <i class="bi bi-exclamation-triangle-fill text-warning mt-1 me-3 fs-5 opacity-75"></i>
                                <div>
                                    <h6 class="text-light mb-1 outfit-semibold">Revisión de Inventario Requerida</h6>
                                    <p class="text-muted small mb-2 outfit-regular">
                                        Actualmente hay <strong class="text-warning">{{ $totalVencidos }} equipo(s)</strong> en tus centros asignados con mantenimientos preventivos vencidos.
                                    </p>
                                    <span class="badge bg-dark border border-secondary text-warning home-notice-time">Alerta Activa</span>
                                </div>
                            </li>
                        @else
                            <li class="list-group-item d-flex align-items-start py-4 px-4 transition-all">
                                <i class="bi bi-shield-check-fill text-success mt-1 me-3 fs-5 opacity-75"></i>
                                <div>
                                    <h6 class="text-light mb-1 outfit-semibold">Infraestructura al Día</h6>
                                    <p class="text-muted small mb-2 outfit-regular">Todos los equipos bajo tu supervisión tienen sus mantenimientos dentro de frecuencia.</p>
                                    <span class="badge bg-dark border border-secondary text-success home-notice-time">Estado Óptimo</span>
                                </div>
                            </li>
                        @endif

                            @foreach($ultimasBitacoras ?? [] as $bit)
                                <li class="list-group-item d-flex align-items-start py-4 px-4 transition-all">
                                    <i class="bi bi-activity text-cyan mt-1 me-3 fs-5 opacity-75"></i>
                                    <div>
                                        <h6 class="text-light mb-1 outfit-semibold">
                                            {{ $bit->modulo ?? 'Sistema' }} — <span class="text-cyan">{{ $bit->evento }}</span>
                                        </h6>
                                        <p class="text-muted small mb-2 outfit-regular">
                                            {{ $bit->descripcion ?? ('Usuario: ' . ($bit->usuario_nombre ?? 'Sistema')) }}
                                        </p>
                                        <span class="badge bg-dark border border-secondary text-cyan home-notice-time">
                                         {{ $bit->created_at ? $bit->created_at->diffForHumans() : 'Reciente' }}
                                         </span>
                                    </div>
                                </li>
                            @endforeach
                    </ul>
                </div>
                <div class="card-footer text-center py-3 home-notice-footer">
                    <a href="{{ route('dashboard') }}" class="text-cyan text-decoration-none small outfit-medium dc-hover-white transition-all d-inline-flex align-items-center gap-1">
                        Ver reporte completo <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
