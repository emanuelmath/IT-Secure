@extends('layouts.layout')

@section('titulo', 'Acerca de IT-Secure | Plataforma NOC')
@section('subtitulo', 'Información del Sistema')
@section('breadcrumb', 'Acerca de')

@section('contenido')
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="dc-card p-4 p-md-5">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <span class="brand-glow-icon dc-icon-lg"><i class="bi bi-cpu-fill"></i></span>
                    <div>
                        <h2 class="h4 text-white outfit-bold mb-0">IT-<span class="text-cyan">SECURE</span></h2>
                        <small class="text-muted">Plataforma de Gestión de Inventario y Mantenimiento de Centros de Cómputo</small>
                    </div>
                </div>

                <p class="text-muted outfit-regular mb-4">
                    IT-Secure centraliza el control de activos tecnológicos, trazabilidad de traslados entre sedes,
                    planificación de mantenimientos preventivos y correctivos, y auditoría de operaciones bajo aislamiento
                    estricto por roles y centros de cómputo asignados.
                </p>

                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="p-3 rounded-3 border border-secondary border-opacity-25 bg-dark bg-opacity-50">
                            <span class="text-muted dc-text-micro text-uppercase d-block mb-1">Versión</span>
                            <strong class="text-cyan font-monospace">v1.0.0 NOC</strong>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 rounded-3 border border-secondary border-opacity-25 bg-dark bg-opacity-50">
                            <span class="text-muted dc-text-micro text-uppercase d-block mb-1">Arquitectura</span>
                            <strong class="text-light">Laravel + MySQL</strong>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 rounded-3 border border-secondary border-opacity-25 bg-dark bg-opacity-50">
                            <span class="text-muted dc-text-micro text-uppercase d-block mb-1">Seguridad</span>
                            <strong class="text-success">RBAC & Multi-Tenant</strong>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('home') }}" class="btn dc-btn-primary px-4">
                        <i class="bi bi-arrow-left me-1"></i> Volver al Inicio
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
