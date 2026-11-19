@extends('layouts.layout')

@section('titulo', 'Historial de Traslados | IT-Secure')
@section('subtitulo', 'Trazabilidad y Movimientos')
@section('breadcrumb', 'Traslados')

@push('estilos')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
@endpush

@section('contenido')
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h1 class="h4 mb-0 text-light outfit-semibold">
            <i class="bi bi-arrow-left-right text-cyan me-2 opacity-75"></i> Movimientos de Infraestructura
        </h1>

        <button type="button" class="btn dc-btn-primary" data-bs-toggle="modal" data-bs-target="#modalRegistrarTraslado">
            <i class="bi bi-plus-lg me-1"></i> Registrar Traslado
        </button>
    </div>

    <div class="dc-card">
        <div class="card-body p-0">
            <div class="table-responsive p-4">
                <table id="tblTraslados" class="table table-hover align-middle w-100 dc-table-dark">
                    <thead>
                    <tr>
                        <th class="dc-th-xs">#</th>
                        <th>Fecha</th>
                        <th>Equipo</th>
                        <th>Centro Origen</th>
                        <th>Centro Destino</th>
                        <th>Motivo</th>
                        <th>Autorizado Por</th>
                        <th class="text-center dc-th-sm">Detalle</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($traslados as $traslado)
                        <tr class="transition-all dc-table-row">
                            <td class="text-muted outfit-light">{{ $traslado->id }}</td>
                            <td class="font-monospace small text-muted dc-font-code">{{ $traslado->fecha_traslado }}</td>
                            <td>
                                <span class="fw-bold text-cyan font-monospace dc-font-code d-block">{{ $traslado->equipo->codigo_inventario ?? 'N/A' }}</span>
                                <small class="text-muted dc-text-micro">{{ $traslado->equipo->marca ?? '' }} {{ $traslado->equipo->modelo ?? '' }}</small>
                            </td>
                            <td>
                                <span class="text-muted d-block outfit-regular">{{ $traslado->centroOrigen->nombre ?? 'N/A' }}</span>
                                <small class="text-muted dc-text-micro">{{ $traslado->centroOrigen->sede->nombre ?? '' }}</small>
                            </td>
                            <td>
                                <span class="text-light d-block outfit-medium">{{ $traslado->centroDestino->nombre ?? 'N/A' }}</span>
                                <small class="text-cyan dc-text-micro">{{ $traslado->centroDestino->sede->nombre ?? '' }}</small>
                            </td>
                            <td class="text-muted small">{{ Str::limit($traslado->motivo, 45) }}</td>
                            <td class="text-light outfit-medium">{{ $traslado->autorizador->nombre ?? 'Sistema' }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('traslados.acta.pdf', $traslado->id) }}" target="_blank" class="btn btn-sm btn-outline-info dc-btn-icon" title="Descargar Acta Oficial de Traslado">
                                        <i class="bi bi-file-earmark-text-fill fs-6"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-info dc-btn-icon btn-ver-traslado"
                                            data-bs-toggle="modal" data-bs-target="#modalDetalleTraslado"
                                            data-fecha="{{ $traslado->fecha_traslado->format('Y-m-d') }}"
                                            data-equipo="{{ $traslado->equipo->codigo_inventario ?? 'N/A' }} — {{ $traslado->equipo->marca ?? '' }} {{ $traslado->equipo->modelo ?? '' }}"
                                            data-origen="{{ ($traslado->centroOrigen->nombre ?? 'N/A') . ' (' . ($traslado->centroOrigen->sede->nombre ?? 'N/A') . ')' }}"
                                            data-destino="{{ ($traslado->centroDestino->nombre ?? 'N/A') . ' (' . ($traslado->centroDestino->sede->nombre ?? 'N/A') . ')' }}"
                                            data-autorizado="{{ $traslado->autorizador->nombre ?? 'N/A' }}"
                                            data-motivo="{{ $traslado->motivo }}"
                                            title="Ver Detalle del Traslado">
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

    <div class="modal fade dc-modal" id="modalRegistrarTraslado" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header border-bottom border-secondary opacity-75">
                    <h5 class="modal-title outfit-semibold text-white">
                        <i class="bi bi-arrow-left-right text-cyan me-2"></i> Ejecutar Traslado de Equipo
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <form action="{{ route('traslados.insertar') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label text-muted text-uppercase tracking-wide outfit-medium dc-text-micro">Equipo a Trasladar</label>
                                <select name="equipo_id" id="selectEquipoTraslado" class="form-select dc-input" required>
                                    <option value="" disabled selected>Seleccione equipo del inventario...</option>
                                    @foreach($equipos as $eq)
                                        <option value="{{ $eq->id }}"
                                                data-origen-id="{{ $eq->centros_de_computo_id }}"
                                                data-origen-nombre="{{ ($eq->centroDeComputo->nombre ?? 'Sin Asignar') . ' — ' . ($eq->centroDeComputo->sede->nombre ?? 'N/A') }}">
                                            {{ $eq->codigo_inventario }} — {{ $eq->marca }} {{ $eq->modelo }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label text-muted text-uppercase tracking-wide outfit-medium dc-text-micro">Fecha de Traslado</label>
                                <input type="date" name="fecha_traslado" class="form-control dc-input" value="{{ date('Y-m-d') }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-muted text-uppercase tracking-wide outfit-medium dc-text-micro">Centro de Origen (Actual)</label>
                                <input type="text" id="displayCentroOrigen" class="form-control dc-input dc-input-readonly" placeholder="Se autocompleta al elegir equipo" readonly>
                                <input type="hidden" name="cc_origen_id" id="hiddenCcOrigenId" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-muted text-uppercase tracking-wide outfit-medium dc-text-micro">Centro de Destino</label>
                                <select name="cc_destino_id" id="selectCentroDestino" class="form-select dc-input" required>
                                    <option value="" disabled selected>Seleccione destino...</option>
                                    @foreach($centros as $centro)
                                        <option value="{{ $centro->id }}">
                                            {{ $centro->nombre }} ({{ $centro->sede->nombre ?? 'N/A' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label text-muted text-uppercase tracking-wide outfit-medium dc-text-micro">Motivo / Justificación del Movimiento</label>
                                <textarea name="motivo" rows="3" class="form-control dc-input" placeholder="Ej: Reasignación por apertura de laboratorio, reemplazo por falla de hardware, redistribución de carga..." required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top border-secondary opacity-75">
                        <button type="button" class="btn dc-btn-outline px-4" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn dc-btn-primary px-4">Confirmar Traslado</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade dc-modal" id="modalDetalleTraslado" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header border-bottom border-secondary opacity-75">
                    <h5 class="modal-title outfit-semibold text-white">
                        <i class="bi bi-journal-check text-cyan me-2"></i> Acta de Movimiento
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3 mb-3">
                        <div class="col-md-8">
                            <span class="d-block text-muted text-uppercase tracking-wide dc-text-micro">Equipo Movilizado</span>
                            <span id="det_traslado_equipo" class="text-cyan fw-bold font-monospace"></span>
                        </div>
                        <div class="col-md-4">
                            <span class="d-block text-muted text-uppercase tracking-wide dc-text-micro">Fecha</span>
                            <span id="det_traslado_fecha" class="text-white font-monospace"></span>
                        </div>
                        <div class="col-md-6">
                            <span class="d-block text-muted text-uppercase tracking-wide dc-text-micro">Ubicación Origen</span>
                            <span id="det_traslado_origen" class="text-muted outfit-medium"></span>
                        </div>
                        <div class="col-md-6">
                            <span class="d-block text-muted text-uppercase tracking-wide dc-text-micro">Ubicación Destino</span>
                            <span id="det_traslado_destino" class="text-white outfit-medium"></span>
                        </div>
                        <div class="col-12">
                            <span class="d-block text-muted text-uppercase tracking-wide dc-text-micro">Autorizado Por</span>
                            <span id="det_traslado_autorizado" class="text-white outfit-medium"></span>
                        </div>
                    </div>
                    <div class="border-top border-secondary opacity-25 my-3"></div>
                    <div>
                        <span class="d-block text-muted text-uppercase tracking-wide dc-text-micro mb-1">Justificación Registrada</span>
                        <p id="det_traslado_motivo" class="text-light mb-0"></p>
                    </div>
                </div>
                <div class="modal-footer border-top border-secondary opacity-75">
                    <button type="button" class="btn dc-btn-outline px-4" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
    <script src="{{ asset('js/traslados-ajax-script.js') }}"></script>
@endpush
