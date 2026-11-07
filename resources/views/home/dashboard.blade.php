@extends('layouts.layout')

@section('titulo', 'Dashboard NOC | IT-Secure')
@section('subtitulo', 'Centro de Operaciones de Red')
@section('breadcrumb', 'Dashboard')

@section('contenido')
    <div class="container-fluid py-2 px-0">

        <div class="row mb-5">
            <div class="col-12">
                <div class="dc-card border-danger-left">
                    <div class="row align-items-stretch m-0">

                        <div class="col-md-3 text-center border-end dc-border-end py-4 px-3 d-flex flex-column justify-content-center bg-danger-subtle-glow">
                            <div class="dc-card-title justify-content-center text-danger mb-3 border-bottom-0 pb-0 pt-0">
                                <i class="bi bi-exclamation-triangle-fill me-2 fs-5 opacity-75"></i> Mantenimientos
                            </div>
                            <div class="alert-number text-danger outfit-light dc-text-huge mb-2">
                                {{ $totalVencidos ?? 0 }}
                            </div>
                            <small class="text-muted text-uppercase tracking-wide outfit-semibold dc-text-micro">Equipos en riesgo inminente</small>
                        </div>

                        <div class="col-md-9 p-0 p-md-4">
                            <div class="dc-table-container mt-3 mt-md-0">
                                <table class="dc-table dc-table-dark w-100 m-0">
                                    <thead>
                                    <tr>
                                        <th>Código Inventario</th>
                                        <th>Marca / Modelo</th>
                                        <th>Sede / Centro</th>
                                        <th class="text-center">Acción</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($equiposVencidos ?? [] as $equipo)
                                        <tr class="transition-all dc-table-row">
                                            <td class="text-cyan font-monospace outfit-semibold dc-font-code">{{ $equipo->codigo_inventario }}</td>
                                            <td class="text-light outfit-regular">{{ $equipo->marca }} {{ $equipo->modelo }}</td>
                                            <td class="text-muted outfit-medium">{{ $equipo->centroDeComputo->nombre ?? 'Sin Sede' }}</td>
                                            <td class="text-center">
                                                <button class="btn btn-sm btn-outline-danger dc-btn-xs rounded-pill px-4 outfit-semibold">Agendar</button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-5 outfit-medium">
                                                <i class="bi bi-check-circle-fill text-success fs-2 d-block mb-3 opacity-75"></i>
                                                Infraestructura al día. No hay mantenimientos vencidos.
                                            </td>
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

        <div class="row mb-5 g-4">
            <div class="col-12 col-lg-5">
                <div class="dc-card h-100 dc-card-hover">
                    <div class="dc-card-title d-flex justify-content-between align-items-center outfit-medium">
                        <div>
                            <span class="status-indicator-live d-inline-block rounded-circle me-2"></span>
                            Distribución por Estado
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="chart-container dc-chart-wrapper">
                            <canvas id="equiposEstadoChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-7">
                <div class="dc-card h-100 dc-card-hover">
                    <div class="dc-card-title text-cyan outfit-medium">
                        <i class="bi bi-pc-display-horizontal me-2 opacity-75"></i> Composición de Hardware
                    </div>
                    <div class="card-body p-4">
                        <div class="chart-container dc-chart-wrapper">
                            <canvas id="equiposTipoChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12 col-lg-7">
                <div class="dc-card h-100 dc-card-hover">
                    <div class="dc-card-title outfit-medium">
                        <i class="bi bi-graph-up-arrow text-cyan me-2 opacity-75"></i> Tendencia: Prev. vs Correctivos
                    </div>
                    <div class="card-body p-4">
                        <div class="chart-container dc-chart-wrapper">
                            <canvas id="mantenimientosChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-5">
                <div class="dc-card h-100 dc-card-hover">
                    <div class="dc-card-title outfit-medium">
                        <i class="bi bi-arrow-left-right text-cyan me-2 opacity-75"></i> Frecuencia de Traslados
                    </div>
                    <div class="card-body p-4">
                        <div class="chart-container dc-chart-wrapper">
                            <canvas id="trasladosChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const rootStyles = getComputedStyle(document.documentElement);
            const colorSurface = rootStyles.getPropertyValue('--dc-bg-surface').trim() || '#0c1220';
            const colorElevated = rootStyles.getPropertyValue('--dc-bg-elevated').trim() || '#111827';
            const colorCyan = rootStyles.getPropertyValue('--dc-cyan').trim() || '#38bdf8';
            const colorTextMain = rootStyles.getPropertyValue('--dc-text-main').trim() || '#f8fafc';
            const colorTextMuted = rootStyles.getPropertyValue('--dc-text-muted').trim() || '#94a3b8';
            const colorBorder = rootStyles.getPropertyValue('--dc-border').trim() || 'rgba(255, 255, 255, 0.07)';
            const colorGridLine = 'rgba(255, 255, 255, 0.03)';

            const tooltipConfig = {
                backgroundColor: 'rgba(17, 24, 39, 0.95)',
                titleColor: colorCyan,
                bodyColor: colorTextMain,
                borderColor: colorBorder,
                borderWidth: 1,
                padding: 14,
                cornerRadius: 10,
                titleFont: { family: "'Outfit', sans-serif", size: 13, weight: '500' },
                bodyFont: { family: "'Outfit', sans-serif", size: 13, weight: '300' },
                boxPadding: 6
            };

            Chart.defaults.font.family = "'Outfit', system-ui, -apple-system, sans-serif";
            Chart.defaults.color = colorTextMuted;

            if(document.getElementById('equiposEstadoChart')) {
                new Chart(document.getElementById('equiposEstadoChart').getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: @json($estadoLabels ?? []),
                        datasets: [{
                            data: @json($estadoData ?? []),
                            backgroundColor: ['#10b981', '#f59e0b', '#ef4444', colorCyan],
                            borderColor: colorElevated,
                            borderWidth: 5,
                            hoverOffset: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '78%',
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: { color: colorTextMain, usePointStyle: true, padding: 25, font: { size: 12, weight: '400' } }
                            },
                            tooltip: tooltipConfig
                        }
                    }
                });
            }

            if(document.getElementById('equiposTipoChart')) {
                new Chart(document.getElementById('equiposTipoChart').getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: @json($tipoLabels ?? []),
                        datasets: [{
                            label: 'Cant. Equipos',
                            data: @json($tipoData ?? []),
                            backgroundColor: 'rgba(56, 189, 248, 0.15)',
                            borderColor: colorCyan,
                            borderWidth: 1,
                            borderRadius: 6,
                            hoverBackgroundColor: 'rgba(56, 189, 248, 0.3)'
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: tooltipConfig
                        },
                        scales: {
                            x: { grid: { color: colorGridLine }, ticks: { color: colorTextMuted, font: { weight: '300' } } },
                            y: { grid: { display: false }, ticks: { color: colorTextMain, font: { weight: '500' } } }
                        }
                    }
                });
            }

            if(document.getElementById('mantenimientosChart')) {
                new Chart(document.getElementById('mantenimientosChart').getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: @json($mesesMant ?? []),
                        datasets: [
                            {
                                label: 'Preventivos',
                                data: @json($mantPreventivos ?? []),
                                borderColor: '#10b981',
                                backgroundColor: 'rgba(16, 185, 129, 0.08)',
                                borderWidth: 2,
                                fill: true,
                                tension: 0.4,
                                pointBackgroundColor: colorElevated,
                                pointBorderWidth: 2,
                                pointRadius: 4,
                                pointHoverRadius: 6
                            },
                            {
                                label: 'Correctivos',
                                data: @json($mantCorrectivos ?? []),
                                borderColor: '#ef4444',
                                backgroundColor: 'rgba(239, 68, 68, 0.08)',
                                borderWidth: 2,
                                fill: true,
                                tension: 0.4,
                                pointBackgroundColor: colorElevated,
                                pointBorderWidth: 2,
                                pointRadius: 4,
                                pointHoverRadius: 6
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: { mode: 'index', intersect: false },
                        plugins: {
                            legend: { position: 'top', align: 'end', labels: { color: colorTextMain, usePointStyle: true, boxWidth: 8, font: { weight: '400' } } },
                            tooltip: tooltipConfig
                        },
                        scales: {
                            x: { grid: { display: false }, ticks: { color: colorTextMuted, font: { weight: '300' } } },
                            y: { grid: { color: colorGridLine }, ticks: { color: colorTextMuted, stepSize: 1, font: { weight: '300' } } }
                        }
                    }
                });
            }

            if(document.getElementById('trasladosChart')) {
                new Chart(document.getElementById('trasladosChart').getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: @json($trasladosLabels ?? []),
                        datasets: [{
                            label: 'Movimientos',
                            data: @json($trasladosData ?? []),
                            backgroundColor: '#3b82f6',
                            borderRadius: 6,
                            hoverBackgroundColor: '#60a5fa'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: tooltipConfig
                        },
                        scales: {
                            x: { grid: { display: false }, ticks: { color: colorTextMuted, font: { weight: '300' } } },
                            y: { grid: { color: colorGridLine }, ticks: { color: colorTextMuted, stepSize: 1, font: { weight: '300' } } }
                        }
                    }
                });
            }
        });
    </script>
@endpush
