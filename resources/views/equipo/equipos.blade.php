@extends('layouts.layout')

@section('titulo', 'Gestión de Equipos | IT-Secure')
@section('subtitulo', 'Inventario de Equipos')
@section('breadcrumb', 'Equipos')

@push('estilos')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
@endpush

@section('contenido')
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h1 class="h4 mb-0 text-light outfit-semibold"><i class="bi bi-pc-display text-cyan me-2 opacity-75"></i> Inventario Completo</h1>

        <div class="d-flex gap-2">
            <a href="{{ route('equipos.export.excel') }}" class="btn btn-outline-success dc-btn-outline">
                <i class="bi bi-file-earmark-excel me-1"></i> Exportar
            </a>

            <button type="button" id="btnNuevoEquipo" class="btn dc-btn-primary" data-bs-toggle="modal" data-bs-target="#modalEquipo">
                <i class="bi bi-plus-lg me-1"></i> Nuevo Equipo
            </button>
        </div>
    </div>

    <div class="dc-card">
        <div class="card-body p-0">
            <div class="table-responsive p-4">
                <table id="tbl" class="table table-hover align-middle w-100 dc-table-dark">
                    <thead>
                    <tr>
                        <th class="dc-th-xs">#</th>
                        <th>Código</th>
                        <th>Tipo</th>
                        <th>Marca / Modelo</th>
                        <th>N° Serie</th>
                        <th>Estado</th>
                        <th>Ubicación</th>
                        <th class="text-center dc-th-sm">Ficha</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($equipos as $eq)
                        <tr class="transition-all dc-table-row">
                            <td class="text-muted outfit-light">{{ $eq->id }}</td>
                            <td><span class="fw-bold text-cyan font-monospace dc-font-code">{{ $eq->codigo_inventario }}</span></td>
                            <td><span class="text-light outfit-medium">{{ ucfirst($eq->tipo) }}</span></td>
                            <td class="text-light outfit-regular">{{ $eq->marca }} {{$eq->modelo }}</td>
                            <td class="font-monospace small text-muted dc-font-code">{{ $eq->numero_serie ?? 'N/A' }}</td>
                            <td>
                                @php
                                    $badgeColor = match($eq->estado) {
                                        'operativo' => 'dc-badge-success',
                                        'mantenimiento' => 'dc-badge-warning',
                                        'averiado' => 'dc-badge-danger',
                                        default => 'dc-badge-secondary'
                                    };
                                @endphp
                                <span class="badge {{ $badgeColor }} rounded-pill px-3 outfit-medium">{{ ucfirst(str_replace('_', ' ', $eq->estado)) }}</span>
                            </td>
                            <td>
                                <span class="text-light d-block outfit-medium">{{ $eq->centroDeComputo->nombre ?? 'Sin Asignar' }}</span>
                                <small class="text-muted dc-text-micro">
                                    {{ $eq->centroDeComputo->sede->nombre ?? 'N/A' }}
                                </small>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('equipos.ficha.pdf', $eq->id) }}" target="_blank" class="btn btn-sm btn-outline-info dc-btn-icon" title="Descargar Ficha Técnica">
                                    <i class="bi bi-file-earmark-pdf-fill fs-6"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
    <script src="{{ asset('js/dataTable.js') }}"></script>
    <script>
        $(document).ready(function() {
            if ($('#tbl').length && !$.fn.DataTable.isDataTable('#tbl')) {
                $('#tbl').DataTable({
                    language: {
                        url: "//cdn.datatables.net/plug-ins/1.13.8/i18n/es-ES.json"
                    }
                });
            }
        });
    </script>
@endpush
