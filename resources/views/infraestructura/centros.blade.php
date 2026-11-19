@extends('layouts.layout')

@section('titulo', 'Centros de Cómputo | IT-Secure')
@section('subtitulo', 'Laboratorios y Salas')
@section('breadcrumb', 'Centros de Cómputo')

@push('estilos')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
@endpush

@section('contenido')
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h1 class="h4 mb-0 text-light outfit-semibold">
            <i class="bi bi-hdd-network text-cyan me-2 opacity-75"></i> Centros y Laboratorios
        </h1>

        <button type="button" class="btn dc-btn-primary" data-bs-toggle="modal" data-bs-target="#modalCrearCentro">
            <i class="bi bi-plus-lg me-1"></i> Nuevo Centro
        </button>
    </div>

    <div class="dc-card">
        <div class="card-body p-0">
            <div class="table-responsive p-4">
                <table id="tblCentros" class="table table-hover align-middle w-100 dc-table-dark">
                    <thead>
                    <tr>
                        <th class="dc-th-xs">#</th>
                        <th>Código</th>
                        <th>Nombre del Centro</th>
                        <th>Sede / Empresa</th>
                        <th>Equipos</th>
                        <th class="text-center dc-th-sm">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($centros as $centro)
                        <tr class="transition-all dc-table-row">
                            <td class="text-muted outfit-light">{{ $centro->id }}</td>
                            <td><span class="fw-bold text-cyan font-monospace dc-font-code">{{ $centro->codigo }}</span></td>
                            <td><span class="text-light outfit-medium">{{ $centro->nombre }}</span></td>
                            <td>
                                <span class="text-light d-block outfit-regular">{{ $centro->sede->nombre ?? 'Sin Sede' }}</span>
                                <small class="text-muted dc-text-micro">{{ $centro->sede->empresa->nombre ?? 'N/A' }}</small>
                            </td>
                            <td>
                                <span class="badge dc-badge-secondary rounded-pill px-3 outfit-medium mb-1 d-inline-block">
                                    {{ $centro->equipos_count ?? $centro->equipos->count() }} equipo(s)
                                </span>
                                @php
                                    $conteoTipos = $centro->equipos ? $centro->equipos->groupBy('tipo')->map->count() : collect();
                                @endphp
                                @if($conteoTipos->isNotEmpty())
                                    <div class="d-flex flex-wrap gap-1 mt-1">
                                        @foreach($conteoTipos as $tipo => $cant)
                                            <span class="badge bg-dark border border-secondary text-light outfit-regular dc-text-micro">
                                                <span class="text-cyan outfit-medium">{{ ucfirst($tipo) }}:</span> {{ $cant }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('centros.reporte.pdf', $centro->id) }}" target="_blank" class="btn btn-sm btn-outline-info dc-btn-icon" title="Generar Informe PDF del Centro">
                                        <i class="bi bi-file-earmark-pdf-fill fs-6"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-warning dc-btn-icon btn-editar-centro"
                                            data-bs-toggle="modal" data-bs-target="#modalEditarCentro"
                                            data-id="{{ $centro->id }}"
                                            data-codigo="{{ $centro->codigo }}"
                                            data-nombre="{{ $centro->nombre }}"
                                            data-sede="{{ $centro->sede_id }}"
                                            title="Editar Centro">
                                        <i class="bi bi-pencil-square fs-6"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger dc-btn-icon btn-eliminar-centro"
                                            data-bs-toggle="modal" data-bs-target="#modalEliminarCentro"
                                            data-id="{{ $centro->id }}"
                                            data-nombre="{{ $centro->nombre }}"
                                            data-codigo="{{ $centro->codigo }}"
                                            title="Eliminar Centro">
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

    <div class="modal fade dc-modal" id="modalCrearCentro" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-bottom border-secondary opacity-75">
                    <h5 class="modal-title outfit-semibold text-white">
                        <i class="bi bi-hdd-network text-cyan me-2"></i> Registrar Centro de Cómputo
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <form action="{{ route('centros.insertar') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label text-muted text-uppercase tracking-wide outfit-medium dc-text-micro">Sede Física</label>
                            <select name="sede_id" class="form-select dc-input" required>
                                <option value="" disabled selected>Seleccione sede...</option>
                                @foreach($sedes as $sede)
                                    <option value="{{ $sede->id }}">{{ $sede->nombre }} ({{ $sede->empresa->nombre ?? '' }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted text-uppercase tracking-wide outfit-medium dc-text-micro">Código Identificador</label>
                            <input type="text" name="codigo" class="form-control dc-input font-monospace" maxlength="50" placeholder="Ej: CC-LAB-01" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label text-muted text-uppercase tracking-wide outfit-medium dc-text-micro">Nombre del Centro / Laboratorio</label>
                            <input type="text" name="nombre" class="form-control dc-input" maxlength="100" placeholder="Ej: Laboratorio de Redes y Servidores" required>
                        </div>
                    </div>
                    <div class="modal-footer border-top border-secondary opacity-75">
                        <button type="button" class="btn dc-btn-outline px-4" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn dc-btn-primary px-4">Guardar Centro</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade dc-modal" id="modalEditarCentro" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-bottom border-secondary opacity-75">
                    <h5 class="modal-title outfit-semibold text-white">
                        <i class="bi bi-pencil-square text-cyan me-2"></i> Editar Centro de Cómputo
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <form id="formEditarCentro" action="#" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label text-muted text-uppercase tracking-wide outfit-medium dc-text-micro">Sede Física</label>
                            <select name="sede_id" id="edit_centro_sede_id" class="form-select dc-input" required>
                                @foreach($sedes as $sede)
                                    <option value="{{ $sede->id }}">{{ $sede->nombre }} ({{ $sede->empresa->nombre ?? '' }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted text-uppercase tracking-wide outfit-medium dc-text-micro">Código Identificador</label>
                            <input type="text" name="codigo" id="edit_centro_codigo" class="form-control dc-input font-monospace" maxlength="50" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label text-muted text-uppercase tracking-wide outfit-medium dc-text-micro">Nombre del Centro / Laboratorio</label>
                            <input type="text" name="nombre" id="edit_centro_nombre" class="form-control dc-input" maxlength="100" required>
                        </div>
                    </div>
                    <div class="modal-footer border-top border-secondary opacity-75">
                        <button type="button" class="btn dc-btn-outline px-4" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn dc-btn-primary px-4">Actualizar Centro</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade dc-modal dc-modal-danger" id="modalEliminarCentro" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body p-4 text-center">
                    <div class="mb-4 mt-2">
                        <i class="bi bi-exclamation-triangle text-danger dc-text-huge"></i>
                    </div>
                    <h5 class="text-white outfit-semibold mb-3">¿Eliminar Centro de Cómputo?</h5>
                    <p class="text-muted mb-4">¿Confirmas eliminar el centro <strong id="deleteCentroNombre" class="text-white"></strong> (<span id="deleteCentroCodigo" class="text-cyan font-monospace"></span>)?</p>
                    <form id="formEliminarCentro" action="#" method="POST">
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
    <script src="{{ asset('js/centros-ajax-script.js') }}"></script>
@endpush
