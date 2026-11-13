@extends('layouts.layout')

@section('titulo', 'Gestión de Sedes | IT-Secure')
@section('subtitulo', 'Sedes Corporativas')
@section('breadcrumb', 'Sedes')

@push('estilos')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
@endpush

@section('contenido')
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h1 class="h4 mb-0 text-light outfit-semibold"><i class="bi bi-buildings text-cyan me-2 opacity-75"></i> Infraestructura Física</h1>

        <button type="button" class="btn dc-btn-primary" data-bs-toggle="modal" data-bs-target="#modalSede">
            <i class="bi bi-plus-lg me-1"></i> Nueva Sede
        </button>
    </div>

    <div class="dc-card">
        <div class="card-body p-0">
            <div class="table-responsive p-4">
                <table id="tblSedes" class="table table-hover align-middle w-100 dc-table-dark">
                    <thead>
                    <tr>
                        <th class="dc-th-xs">ID</th>
                        <th>Nombre de Sede</th>
                        <th>Empresa Asociada</th>
                        <th>Fecha de Registro</th>
                        <th class="text-center dc-th-sm">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <!-- Aquí iterarás sobre la variable $sedes -->
                    <tr class="transition-all dc-table-row">
                        <td class="text-muted outfit-light">1</td>
                        <td><span class="text-light outfit-medium">Edificio Central</span></td>
                        <td class="text-muted">Corporación Tech IT</td>
                        <td class="text-muted dc-font-code small">2023-10-12</td>
                        <td>
                            <div class="d-flex justify-content-center gap-2">
                                <button class="btn btn-sm btn-outline-warning dc-btn-icon" title="Editar Sede"><i class="bi bi-pencil-square fs-6"></i></button>
                                <button class="btn btn-sm btn-outline-danger dc-btn-icon" title="Eliminar Sede"><i class="bi bi-trash fs-6"></i></button>
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
            if ($('#tblSedes').length && !$.fn.DataTable.isDataTable('#tblSedes')) {
                $('#tblSedes').DataTable({ language: { url: "//cdn.datatables.net/plug-ins/1.13.8/i18n/es-ES.json" } });
            }
        });
    </script>
@endpush
