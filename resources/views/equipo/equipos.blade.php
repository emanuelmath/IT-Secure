@extends('layouts.layout')

@section('titulo', 'Gestión de Equipos - IT-Secure')

@push('estilos')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
@endpush

@section('contenido')
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1 class="h3 mb-0 text-light"><i class="bi bi-pc-display text-cyan me-2"></i> Inventario de Equipos</h1>

        <div>
            <a href="{{ route('equipos.export.excel') }}" class="btn btn-outline-success me-2">
                <i class="bi bi-file-earmark-excel me-1"></i> Exportar Excel
            </a>

            <button type="button" id="btnNuevoEquipo" class="btn btn-primary" style="background-color: var(--dc-blue-electric); border: none;" data-bs-toggle="modal" data-bs-target="#modalEquipo">
                <i class="bi bi-plus-lg"></i> Nuevo Equipo
            </button>
        </div>
    </div>

    <div class="card shadow-sm dc-card">
        <div class="card-body">
            <table id="tbl" class="table table-hover table-striped align-middle w-100" style="color: var(--dc-text-main);">
                <thead class="table-dark" style="background-color: var(--dc-bg-surface);">
                <tr>
                    <th style="width: 3rem;">#</th>
                    <th>Código Inventario</th>
                    <th>Tipo</th>
                    <th>Marca / Modelo</th>
                    <th>N° Serie</th>
                    <th>Estado</th>
                    <th>Sede / Centro de Cómputo</th>
                    <th class="text-center" style="width: 7rem;">Acciones</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($equipos as $eq)
                    <tr>
                        <td class="text-muted">{{ $eq->id }}</td>
                        <td><span class="fw-semibold text-cyan font-monospace">{{ $eq->codigo_inventario }}</span></td>
                        <td>{{ ucfirst($eq->tipo) }}</td>
                        <td>{{ $eq->marca }} {{ $eq->modelo }}</td>
                        <td class="font-monospace small text-muted">{{ $eq->numero_serie ?? 'N/A' }}</td>
                        <td>
                            @php
                                $badgeColor = match($eq->estado) {
                                    'operativo' => 'bg-success',
                                    'mantenimiento' => 'bg-warning text-dark',
                                    'averiado' => 'bg-danger',
                                    default => 'bg-secondary'
                                };
                            @endphp
                            <span class="badge {{ $badgeColor }}">{{ ucfirst(str_replace('_', ' ', $eq->estado)) }}</span>
                        </td>
                        <td>
                            {{ $eq->centroDeComputo->nombre ?? 'Sin Asignar' }}
                            <small class="text-muted d-block" style="font-size: 0.75rem;">
                                {{ $eq->centroDeComputo->sede->nombre ?? '' }}
                            </small>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('equipos.ficha.pdf', $eq->id) }}" target="_blank" class="btn btn-sm btn-outline-info" title="Descargar Ficha Técnica PDF">
                                <i class="bi bi-file-earmark-pdf"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
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
