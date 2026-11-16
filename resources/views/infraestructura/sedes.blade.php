@extends('layouts.layout')

@section('titulo', 'Gestión de Sedes | IT-Secure')
@section('subtitulo', 'Sedes Corporativas')
@section('breadcrumb', 'Sedes')

@push('estilos')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
@endpush

@section('contenido')
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h1 class="h4 mb-0 text-light outfit-semibold">
            <i class="bi bi-buildings text-cyan me-2 opacity-75"></i> Infraestructura Física
        </h1>

        <button type="button" class="btn dc-btn-primary" data-bs-toggle="modal" data-bs-target="#modalCrearSede">
            <i class="bi bi-plus-lg me-1"></i> Nueva Sede
        </button>
    </div>

    <div class="dc-card">
        <div class="card-body p-0">
            <div class="table-responsive p-4">
                <table id="tblSedes" class="table table-hover align-middle w-100 dc-table-dark">
                    <thead>
                    <tr>
                        <th class="dc-th-xs">#</th>
                        <th>Nombre de Sede</th>
                        <th>Empresa Asociada</th>
                        <th>Centros Activos</th>
                        <th>Fecha de Registro</th>
                        <th class="text-center dc-th-sm">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($sedes as $sede)
                        <tr class="transition-all dc-table-row">
                            <td class="text-muted outfit-light">{{ $sede->id }}</td>
                            <td><span class="text-light outfit-medium">{{ $sede->nombre }}</span></td>
                            <td class="text-cyan outfit-medium">{{ $sede->empresa->nombre ?? 'Sin Empresa' }}</td>
                            <td>
                                <span class="badge dc-badge-secondary rounded-pill px-3 outfit-medium">
                                    {{ $sede->centros_de_computo_count ?? $sede->centrosDeComputo->count() }} centro(s)
                                </span>
                            </td>
                            <td class="text-muted dc-font-code small">
                                {{ $sede->created_at ? $sede->created_at->format('Y-m-d') : 'N/A' }}
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-warning dc-btn-icon btn-editar-sede"
                                            data-bs-toggle="modal" data-bs-target="#modalEditarSede"
                                            data-id="{{ $sede->id }}"
                                            data-nombre="{{ $sede->nombre }}"
                                            data-empresa="{{ $sede->empresa_id }}"
                                            title="Editar Sede">
                                        <i class="bi bi-pencil-square fs-6"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger dc-btn-icon btn-eliminar-sede"
                                            data-bs-toggle="modal" data-bs-target="#modalEliminarSede"
                                            data-id="{{ $sede->id }}"
                                            data-nombre="{{ $sede->nombre }}"
                                            title="Eliminar Sede">
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

    <div class="modal fade dc-modal" id="modalCrearSede" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-bottom border-secondary opacity-75">
                    <h5 class="modal-title outfit-semibold text-white">
                        <i class="bi bi-building-add text-cyan me-2"></i> Registrar Nueva Sede
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <form action="{{ route('sedes.insertar') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label text-muted text-uppercase tracking-wide outfit-medium dc-text-micro">Empresa Perteneciente</label>
                            <select name="empresa_id" class="form-select dc-input" required>
                                <option value="" disabled selected>Seleccione empresa...</option>
                                @foreach($empresas as $empresa)
                                    <option value="{{ $empresa->id }}">{{ $empresa->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-2">
                            <label class="form-label text-muted text-uppercase tracking-wide outfit-medium dc-text-micro">Nombre de la Sede</label>
                            <input type="text" name="nombre" class="form-control dc-input" maxlength="100" placeholder="Ej: Edificio Central - San Salvador" required>
                        </div>
                    </div>
                    <div class="modal-footer border-top border-secondary opacity-75">
                        <button type="button" class="btn dc-btn-outline px-4" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn dc-btn-primary px-4">Guardar Sede</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade dc-modal" id="modalEditarSede" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-bottom border-secondary opacity-75">
                    <h5 class="modal-title outfit-semibold text-white">
                        <i class="bi bi-pencil-square text-cyan me-2"></i> Editar Sede
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <form id="formEditarSede" action="#" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label text-muted text-uppercase tracking-wide outfit-medium dc-text-micro">Empresa Perteneciente</label>
                            <select name="empresa_id" id="edit_sede_empresa_id" class="form-select dc-input" required>
                                @foreach($empresas as $empresa)
                                    <option value="{{ $empresa->id }}">{{ $empresa->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-2">
                            <label class="form-label text-muted text-uppercase tracking-wide outfit-medium dc-text-micro">Nombre de la Sede</label>
                            <input type="text" name="nombre" id="edit_sede_nombre" class="form-control dc-input" maxlength="100" required>
                        </div>
                    </div>
                    <div class="modal-footer border-top border-secondary opacity-75">
                        <button type="button" class="btn dc-btn-outline px-4" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn dc-btn-primary px-4">Actualizar Sede</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade dc-modal dc-modal-danger" id="modalEliminarSede" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body p-4 text-center">
                    <div class="mb-4 mt-2">
                        <i class="bi bi-exclamation-triangle text-danger dc-text-huge"></i>
                    </div>
                    <h5 class="text-white outfit-semibold mb-3">¿Eliminar Sede?</h5>
                    <p class="text-muted mb-4">¿Confirmas eliminar la sede <strong id="deleteSedeNombre" class="text-white"></strong>?</p>
                    <form id="formEliminarSede" action="#" method="POST">
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
    <script src="{{ asset('js/sedes-ajax-script.js') }}"></script>
@endpush
