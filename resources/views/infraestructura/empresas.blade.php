@extends('layouts.layout')

@section('titulo', 'Gestión de Empresas | IT-Secure')
@section('subtitulo', 'Organizaciones y Clientes')
@section('breadcrumb', 'Empresas')

@push('estilos')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
@endpush

@section('contenido')
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h1 class="h4 mb-0 text-light outfit-semibold">
            <i class="bi bi-building text-cyan me-2 opacity-75"></i> Empresas Registradas
        </h1>

        <button type="button" class="btn dc-btn-primary" data-bs-toggle="modal" data-bs-target="#modalCrearEmpresa">
            <i class="bi bi-plus-lg me-1"></i> Nueva Empresa
        </button>
    </div>

    <div class="dc-card">
        <div class="card-body p-0">
            <div class="table-responsive p-4">
                <table id="tblEmpresas" class="table table-hover align-middle w-100 dc-table-dark">
                    <thead>
                    <tr>
                        <th class="dc-th-xs">#</th>
                        <th>Nombre / Razón Social</th>
                        <th>NIT</th>
                        <th>Sedes Activas</th>
                        <th>Fecha Registro</th>
                        <th class="text-center dc-th-sm">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($empresas as $emp)
                        <tr class="transition-all dc-table-row">
                            <td class="text-muted outfit-light">{{ $emp->id }}</td>
                            <td><span class="text-light outfit-medium">{{ $emp->nombre }}</span></td>
                            <td><span class="fw-bold text-cyan font-monospace dc-font-code">{{ $emp->nit }}</span></td>
                            <td>
                                <span class="badge dc-badge-secondary rounded-pill px-3 outfit-medium">
                                    {{ $emp->sedes_count ?? $emp->sedes->count() }} sede(s)
                                </span>
                            </td>
                            <td class="font-monospace small text-muted dc-font-code">
                                {{ $emp->created_at ? $emp->created_at->format('Y-m-d') : 'N/A' }}
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-warning dc-btn-icon btn-editar-empresa"
                                            data-bs-toggle="modal" data-bs-target="#modalEditarEmpresa"
                                            data-id="{{ $emp->id }}"
                                            data-nombre="{{ $emp->nombre }}"
                                            data-nit="{{ $emp->nit }}"
                                            title="Editar Empresa">
                                        <i class="bi bi-pencil-square fs-6"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger dc-btn-icon btn-eliminar-empresa"
                                            data-bs-toggle="modal" data-bs-target="#modalEliminarEmpresa"
                                            data-id="{{ $emp->id }}"
                                            data-nombre="{{ $emp->nombre }}"
                                            title="Eliminar Empresa">
                                        <i class="bi bi-trash fs-6"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade dc-modal" id="modalCrearEmpresa" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-bottom border-secondary opacity-75">
                    <h5 class="modal-title outfit-semibold text-white">
                        <i class="bi bi-building-add text-cyan me-2"></i> Registrar Empresa
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <form action="{{ route('empresas.insertar') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label text-muted text-uppercase tracking-wide outfit-medium dc-text-micro">Nombre o Razón Social</label>
                            <input type="text" name="nombre" class="form-control dc-input" maxlength="150" placeholder="Ej: Corporación Tecnológica S.A. de C.V." required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label text-muted text-uppercase tracking-wide outfit-medium dc-text-micro">NIT</label>
                            <input type="text" name="nit" class="form-control dc-input font-monospace" maxlength="20" placeholder="Ej: 0614-150895-102-3" required>
                        </div>
                    </div>
                    <div class="modal-footer border-top border-secondary opacity-75">
                        <button type="button" class="btn dc-btn-outline px-4" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn dc-btn-primary px-4">Guardar Empresa</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade dc-modal" id="modalEditarEmpresa" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-bottom border-secondary opacity-75">
                    <h5 class="modal-title outfit-semibold text-white">
                        <i class="bi bi-pencil-square text-cyan me-2"></i> Editar Empresa
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <form id="formEditarEmpresa" action="#" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label text-muted text-uppercase tracking-wide outfit-medium dc-text-micro">Nombre o Razón Social</label>
                            <input type="text" name="nombre" id="edit_empresa_nombre" class="form-control dc-input" maxlength="150" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label text-muted text-uppercase tracking-wide outfit-medium dc-text-micro">NIT</label>
                            <input type="text" name="nit" id="edit_empresa_nit" class="form-control dc-input font-monospace" maxlength="20" required>
                        </div>
                    </div>
                    <div class="modal-footer border-top border-secondary opacity-75">
                        <button type="button" class="btn dc-btn-outline px-4" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn dc-btn-primary px-4">Actualizar Empresa</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade dc-modal dc-modal-danger" id="modalEliminarEmpresa" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body p-4 text-center">
                    <div class="mb-4 mt-2">
                        <i class="bi bi-exclamation-triangle text-danger dc-text-huge"></i>
                    </div>
                    <h5 class="text-white outfit-semibold mb-3">¿Dar de Baja Empresa?</h5>
                    <p class="text-muted mb-4">¿Confirmas que deseas eliminar la empresa <strong id="deleteEmpresaNombre" class="text-white"></strong>? Sus sedes asociadas quedarán huérfanas si no se reasignan.</p>
                    <form id="formEliminarEmpresa" action="#" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="d-flex justify-content-center gap-3">
                            <button type="button" class="btn dc-btn-outline px-4" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn dc-btn-danger px-4">Sí, Eliminar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
    <script src="{{ asset('js/empresas-ajax-script.js') }}"></script>
@endpush
