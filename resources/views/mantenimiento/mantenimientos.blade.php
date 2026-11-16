@extends('layouts.layout')

@section('titulo', 'Gestión de Mantenimientos | IT-Secure')
@section('subtitulo', 'Bitácora de Mantenimientos')
@section('breadcrumb', 'Mantenimientos')

@push('estilos')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
@endpush

@section('contenido')
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h1 class="h4 mb-0 text-light outfit-semibold">
            <i class="bi bi-wrench-adjustable text-cyan me-2 opacity-75"></i> Registro de Intervenciones
        </h1>

        <button type="button" class="btn dc-btn-primary" data-bs-toggle="modal" data-bs-target="#modalRegistrarMantenimiento">
            <i class="bi bi-plus-lg me-1"></i> Nuevo Mantenimiento
        </button>
    </div>

    <div class="dc-card">
        <div class="card-body p-0">
            <div class="table-responsive p-4">
                <table id="tblMantenimientos" class="table table-hover align-middle w-100 dc-table-dark">
                    <thead>
                    <tr>
                        <th class="dc-th-xs">#</th>
                        <th>Fecha</th>
                        <th>Equipo</th>
                        <th>Tipo</th>
                        <th>Trabajo Realizado</th>
                        <th>Piezas Reemplazadas</th>
                        <th>Técnico</th>
                        <th class="text-center dc-th-sm">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($mantenimientos as $mant)
                        <tr class="transition-all dc-table-row">
                            <td class="text-muted outfit-light">{{ $mant->id }}</td>
                            <td class="font-monospace small text-muted dc-font-code">{{ $mant->fecha_mantenimiento }}</td>
                            <td>
                                <span class="fw-bold text-cyan font-monospace dc-font-code d-block">{{ $mant->equipo->codigo_inventario ?? 'N/A' }}</span>
                                <small class="text-muted dc-text-micro">{{ $mant->equipo->marca ?? '' }} {{ $mant->equipo->modelo ?? '' }}</small>
                            </td>
                            <td>
                                @php
                                    $badgeTipo = $mant->tipo === 'preventivo' ? 'dc-badge-success' : 'dc-badge-warning';
                                @endphp
                                <span class="badge {{ $badgeTipo }} rounded-pill px-3 outfit-medium">{{ ucfirst($mant->tipo) }}</span>
                            </td>
                            <td class="text-light outfit-regular">{{ Str::limit($mant->descripcion_trabajo, 55) }}</td>
                            <td class="text-muted small">{{ $mant->piezas_reemplazadas ?: 'Ninguna' }}</td>
                            <td class="text-light outfit-medium">{{ $mant->usuario->nombre ?? 'Sistema' }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-info dc-btn-icon btn-ver-mantenimiento"
                                            data-bs-toggle="modal" data-bs-target="#modalDetalleMantenimiento"
                                            data-fecha="{{ $mant->fecha_mantenimiento }}"
                                            data-equipo="{{ $mant->equipo->codigo_inventario ?? 'N/A' }} - {{ $mant->equipo->marca ?? '' }} {{ $mant->equipo->modelo ?? '' }}"
                                            data-centro="{{ $mant->equipo->centroDeComputo->nombre ?? 'N/A' }}"
                                            data-tipo="{{ ucfirst($mant->tipo) }}"
                                            data-tecnico="{{ $mant->usuario->nombre ?? 'N/A' }}"
                                            data-descripcion="{{ $mant->descripcion_trabajo }}"
                                            data-piezas="{{ $mant->piezas_reemplazadas ?: 'Sin reemplazo de componentes' }}"
                                            title="Ver Detalle Completo">
                                        <i class="bi bi-eye fs-6"></i>
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

    <!-- Modal Registrar Mantenimiento -->
    <div class="modal fade dc-modal" id="modalRegistrarMantenimiento" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header border-bottom border-secondary opacity-75">
                    <h5 class="modal-title outfit-semibold text-white">
                        <i class="bi bi-tools text-cyan me-2"></i> Registrar Mantenimiento
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <form action="{{ route('mantenimientos.insertar') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted text-uppercase tracking-wide outfit-medium dc-text-micro">Equipo Intervenido</label>
                                <select name="equipo_id" class="form-select dc-input" required>
                                    <option value="" disabled selected>Seleccione equipo...</option>
                                    @foreach($equipos as $eq)
                                        <option value="{{ $eq->id }}">
                                            {{ $eq->codigo_inventario }} — {{ $eq->marca }} {{ $eq->modelo }} ({{ $eq->centroDeComputo->nombre ?? 'Sin centro' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label text-muted text-uppercase tracking-wide outfit-medium dc-text-micro">Tipo</label>
                                <select name="tipo" class="form-select dc-input" required>
                                    <option value="preventivo">Preventivo</option>
                                    <option value="correctivo">Correctivo</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label text-muted text-uppercase tracking-wide outfit-medium dc-text-micro">Fecha</label>
                                <input type="date" name="fecha_mantenimiento" class="form-control dc-input" value="{{ date('Y-m-d') }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-muted text-uppercase tracking-wide outfit-medium dc-text-micro">Nuevo Estado del Equipo</label>
                                <select name="estado_equipo" class="form-select dc-input" required>
                                    <option value="operativo" selected>Operativo (Listo para uso)</option>
                                    <option value="mantenimiento">En Mantenimiento (Aún en taller)</option>
                                    <option value="averiado">Averiado (Requiere pieza/baja)</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-muted text-uppercase tracking-wide outfit-medium dc-text-micro">Piezas Reemplazadas (Opcional)</label>
                                <input type="text" name="piezas_reemplazadas" class="form-control dc-input" placeholder="Ej: Pasta térmica, SSD 512GB, Fuente ATX">
                            </div>

                            <div class="col-12">
                                <label class="form-label text-muted text-uppercase tracking-wide outfit-medium dc-text-micro">Descripción del Trabajo Realizado</label>
                                <textarea name="descripcion_trabajo" rows="4" class="form-control dc-input" placeholder="Detalla el diagnóstico, limpieza, pruebas y solución aplicada..." required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top border-secondary opacity-75">
                        <button type="button" class="btn dc-btn-outline px-4" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn dc-btn-primary px-4">Guardar Registro</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Ver Detalle de Mantenimiento -->
    <div class="modal fade dc-modal" id="modalDetalleMantenimiento" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header border-bottom border-secondary opacity-75">
                    <h5 class="modal-title outfit-semibold text-white">
                        <i class="bi bi-file-text text-cyan me-2"></i> Detalle de Intervención
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <span class="d-block text-muted text-uppercase tracking-wide dc-text-micro">Equipo</span>
                            <span id="det_equipo" class="text-cyan fw-bold font-monospace"></span>
                        </div>
                        <div class="col-md-6">
                            <span class="d-block text-muted text-uppercase tracking-wide dc-text-micro">Centro de Cómputo</span>
                            <span id="det_centro" class="text-white outfit-medium"></span>
                        </div>
                        <div class="col-md-4">
                            <span class="d-block text-muted text-uppercase tracking-wide dc-text-micro">Fecha</span>
                            <span id="det_fecha" class="text-white font-monospace"></span>
                        </div>
                        <div class="col-md-4">
                            <span class="d-block text-muted text-uppercase tracking-wide dc-text-micro">Tipo</span>
                            <span id="det_tipo" class="text-white outfit-medium"></span>
                        </div>
                        <div class="col-md-4">
                            <span class="d-block text-muted text-uppercase tracking-wide dc-text-micro">Técnico Responsable</span>
                            <span id="det_tecnico" class="text-white outfit-medium"></span>
                        </div>
                    </div>
                    <div class="border-top border-secondary opacity-25 my-3"></div>
                    <div class="mb-3">
                        <span class="d-block text-muted text-uppercase tracking-wide dc-text-micro mb-1">Piezas Reemplazadas</span>
                        <p id="det_piezas" class="text-light mb-0"></p>
                    </div>
                    <div>
                        <span class="d-block text-muted text-uppercase tracking-wide dc-text-micro mb-1">Informe Técnico</span>
                        <p id="det_descripcion" class="text-muted mb-0"></p>
                    </div>
                </div>
                <div class="modal-footer border-top border-secondary opacity-75">
                    <button type="button" class="btn dc-btn-danger px-4" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
    <script src="{{ asset('js/mantenimientos-ajax-script.js') }}"></script>
@endpush
