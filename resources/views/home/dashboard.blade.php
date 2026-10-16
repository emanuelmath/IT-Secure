@extends('layouts.layout')

@section('titulo', 'Dashboard - NOC')

<link rel="stylesheet" href="{{ asset('css/dashboard-theme.css') }}" />

@section('contenido')
    <style>
        .dc-table-container { overflow-x: auto; }
        .dc-table { width: 100%; border-collapse: collapse; text-align: left; }
        .dc-table th { color: var(--dc-text-muted); font-size: 0.8rem; text-transform: uppercase; padding: 12px; border-bottom: 1px solid var(--dc-border); }
        .dc-table td { color: var(--dc-text-main); font-size: 0.9rem; padding: 12px; border-bottom: 1px solid rgba(56,189,248,0.05); }
        .dc-table tr:hover { background-color: rgba(56,189,248,0.03); }
        .alert-number { font-size: 2.5rem; font-weight: bold; color: #ef4444; line-height: 1; margin-bottom: 5px; }
    </style>

    <div class="container-fluid py-4">

        <div class="row mb-4">
            <div class="col-12">
                <div class="dc-card" style="border-left: 4px solid #ef4444;">
                    <div class="row align-items-center">
                        <div class="col-md-3 text-center border-end" style="border-color: var(--dc-border) !important;">
                            <div class="dc-card-title justify-content-center text-danger mb-2">
                                <i class="fas fa-exclamation-triangle me-2"></i> Mantenimientos Vencidos
                            </div>
                            <div class="alert-number">{{ $totalVencidos }}</div>
                            <small class="text-muted">Equipos en riesgo inminente</small>
                        </div>
                        <div class="col-md-9">
                            <div class="dc-table-container">
                                <table class="dc-table">
                                    <thead>
                                    <tr>
                                        <th>Código Inventario</th>
                                        <th>Marca / Modelo</th>
                                        <th>Sede / Centro</th>
                                        <th>Acción</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($equiposVencidos as $equipo)
                                        <tr>
                                            <td class="text-cyan">{{ $equipo->codigo_inventario }}</td>
                                            <td>{{ $equipo->marca }} {{ $equipo->modelo }}</td>
                                            <td>{{ $equipo->centroDeComputo->nombre ?? 'Sin Sede' }}</td>
                                            <td><button class="btn btn-sm btn-outline-danger" style="font-size: 0.75rem;">Agendar</button></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">Infraestructura al día. Sin mantenimientos vencidos.</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12 col-lg-5 mb-4 mb-lg-0">
                <div class="dc-card h-100">
                    <div class="dc-card-title">
                        <span class="status-indicator-live"></span>
                        Distribución por Estado
                    </div>
                    <div class="chart-container">
                        <canvas id="equiposEstadoChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-7">
                <div class="dc-card h-100">
                    <div class="dc-card-title text-cyan">
                        Composición de Hardware (Tipos)
                    </div>
                    <div class="chart-container">
                        <canvas id="equiposTipoChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-7 mb-4 mb-lg-0">
                <div class="dc-card h-100">
                    <div class="dc-card-title">
                        Tendencia Prev. vs Correctivos
                    </div>
                    <div class="chart-container">
                        <canvas id="mantenimientosChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-5">
                <div class="dc-card h-100">
                    <div class="dc-card-title">
                        Frecuencia de Traslados
                    </div>
                    <div class="chart-container">
                        <canvas id="trasladosChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const colorSurface = '#111a2e';
            const colorCyan = '#38bdf8';
            const colorTextMain = '#f1f5f9';
            const colorTextMuted = '#8493a8';
            const colorGridLine = 'rgba(56, 189, 248, 0.05)';

            const tooltipConfig = {
                backgroundColor: 'rgba(12, 18, 32, 0.95)',
                titleColor: colorCyan,
                bodyColor: colorTextMain,
                borderColor: 'rgba(56, 189, 248, 0.25)',
                borderWidth: 1,
                padding: 12,
                cornerRadius: 8
            };

            new Chart(document.getElementById('equiposEstadoChart').getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: @json($estadoLabels),
                    datasets: [{
                        data: @json($estadoData),
                        backgroundColor: ['#10b981', '#f59e0b', '#ef4444', '#38bdf8'],
                        borderColor: colorSurface,
                        borderWidth: 4,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    cutout: '75%',
                    plugins: {
                        legend: { position: 'bottom', labels: { color: colorTextMuted, usePointStyle: true } },
                        tooltip: tooltipConfig
                    }
                }
            });

            new Chart(document.getElementById('equiposTipoChart').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: @json($tipoLabels),
                    datasets: [{
                        label: 'Cant. Equipos',
                        data: @json($tipoData),
                        backgroundColor: 'rgba(56, 189, 248, 0.2)',
                        borderColor: colorCyan,
                        borderWidth: 1,
                        borderRadius: 4
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true, maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: tooltipConfig
                    },
                    scales: {
                        x: { grid: { color: colorGridLine }, ticks: { color: colorTextMuted } },
                        y: { grid: { display: false }, ticks: { color: colorTextMain } }
                    }
                }
            });

            new Chart(document.getElementById('mantenimientosChart').getContext('2d'), {
                type: 'line',
                data: {
                    labels: @json($mesesMant),
                    datasets: [
                        {
                            label: 'Preventivos',
                            data: @json($mantPreventivos),
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Correctivos',
                            data: @json($mantCorrectivos),
                            borderColor: '#ef4444',
                            backgroundColor: 'rgba(239, 68, 68, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { position: 'top', labels: { color: colorTextMuted, usePointStyle: true } }, tooltip: tooltipConfig },
                    scales: {
                        x: { grid: { display: false }, ticks: { color: colorTextMuted } },
                        y: { grid: { color: colorGridLine }, ticks: { color: colorTextMuted, stepSize: 1 } }
                    }
                }
            });

            new Chart(document.getElementById('trasladosChart').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: @json($trasladosLabels),
                    datasets: [{
                        label: 'Movimientos',
                        data: @json($trasladosData),
                        backgroundColor: '#2563eb',
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { display: false }, tooltip: tooltipConfig },
                    scales: {
                        x: { grid: { display: false }, ticks: { color: colorTextMuted } },
                        y: { grid: { color: colorGridLine }, ticks: { color: colorTextMuted, stepSize: 1 } }
                    }
                }
            });
        });
    </script>
@endsection
