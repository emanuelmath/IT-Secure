@extends('layouts.layout')

@section('titulo', 'Centros de Cómputo | IT-Secure')
@section('subtitulo', 'Laboratorios y Salas')
@section('breadcrumb', 'Centros de Cómputo')

@push('estilos')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
@endpush

@section('contenido')
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h1 class="h4 mb-0 text-light outfit-semibold"><i class="bi bi-hdd-network text-cyan me-2 opacity-75"></i> Centros y Laboratorios</h1>

        <button type="button" class="btn dc-btn-primary" data-bs-toggle="modal" data-bs-target="#modalCentro">
            <i class="bi bi-plus-lg me-1"></i> Nuevo Centro
        </button>
    </div>

    <div class="dc-card">
        <div class="card-body p-0">
            <div class="table-responsive p-4">
                <table id="tblCentros" class="table table-hover align-middle w-100 dc-table-dark">
                    <thead>
                    <tr>
                        <th class="dc-th-xs">ID</th>
                        <th>Código Centro</th>
                        <th>Nombre</th>
                        <th>Sede (Ubicación)</th>
                        <th class="text-center dc-th-sm">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <!-- Aquí iterarás sobre la variable $centros -->
                    <tr class="transition-all dc-table-row">
                        <td class="text-muted outfit-light">1</td>
                        <td><span class="fw-bold text-cyan font-monospace dc-font-code">LAB-01</span></td>
                        <td><span class="text-light outfit-medium">Laboratorio Principal de Redes</span></td>
                        <td><span class="text-muted">Edificio Central</span></td>
                        <td>
                            <div class="d-flex justify-content-center gap-2">
                                <button class="btn btn-sm btn-outline-info dc-btn-icon" title="Ver Detalles"><i class="bi bi-eye fs-6"></i></button>
                                <button class="btn btn-sm btn-outline-warning dc-btn-icon" title="Editar"><i class="bi bi-pencil-square fs-6"></i></button>
                                <button class="btn btn-sm btn-outline-danger dc-btn-icon" title="Eliminar"><i class="bi bi-trash fs-6"></i></button>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            if ($('#tblCentros').length && !$.fn.DataTable.isDataTable('#tblCentros')) {
                $('#tblCentros').DataTable({ language: { url: "//cdn.datatables.net/plug-ins/1.13.8/i18n/es-ES.json" } });
            }
        });
    </script>
@endpush
