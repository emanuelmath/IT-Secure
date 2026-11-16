@extends('layouts.layout')

@section('titulo', 'Bitácora de Auditoría | IT-Secure')
@section('subtitulo', 'Registro de Eventos del Sistema')
@section('breadcrumb', 'Bitácoras')

@push('estilos')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
@endpush

@section('contenido')
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h1 class="h4 mb-0 text-light outfit-semibold">
            <i class="bi bi-shield-check text-cyan me-2 opacity-75"></i> Trazabilidad de Operaciones
        </h1>
        <span class="badge bg-dark border border-secondary text-muted outfit-medium px-3 py-2">
            <i class="bi bi-lock-fill text-cyan me-1"></i> Registro Inmutable de Auditoría
        </span>
    </div>

    <div class="dc-card">
        <div class="card-body p-0">
            <div class="table-responsive p-4">
                <table id="tblBitacoras" class="table table-hover align-middle w-100 dc-table-dark">
                    <thead>
                    <tr>
                        <th class="dc-th-xs">#</th>
                        <th>Fecha / Hora</th>
                        <th>Usuario</th>
                        <th>Módulo</th>
                        <th>Evento</th>
                        <th>ID Reg.</th>
                        <th>IP Origen</th>
                        <th class="text-center dc-th-sm">Detalle</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($bitacoras as $log)
                        <tr class="transition-all dc-table-row">
                            <td class="text-muted outfit-light">{{ $log->id }}</td>
                            <td class="font-monospace small text-muted dc-font-code">
                                {{ $log->created_at ? $log->created_at->format('Y-m-d H:i:s') : 'N/A' }}
                            </td>
                            <td>
                                <span class="text-light outfit-medium d-block">{{ $log->usuario_nombre ?? 'Sistema' }}</span>
                                <small class="text-muted dc-text-micro">ID: {{ $log->usuario_id ?? 'N/A' }}</small>
                            </td>
                            <td><span class="text-cyan outfit-medium text-uppercase dc-text-micro tracking-wide">{{ $log->modulo }}</span></td>
                            <td>
                                @php
                                    $eventoLower = strtolower($log->evento);
                                    $badgeEvento = match(true) {
                                        str_contains($eventoLower, 'crear') || str_contains($eventoLower, 'insert') => 'dc-badge-success',
                                        str_contains($eventoLower, 'edit') || str_contains($eventoLower, 'actualiz') => 'dc-badge-warning',
                                        str_contains($eventoLower, 'elimin') || str_contains($eventoLower, 'delete') || str_contains($eventoLower, 'baja') => 'dc-badge-danger',
                                        default => 'dc-badge-secondary'
                                    };
                                @endphp
                                <span class="badge {{ $badgeEvento }} rounded-pill px-3 outfit-medium">{{ ucfirst($log->evento) }}</span>
                            </td>
                            <td class="font-monospace small text-light dc-font-code">{{ $log->registro_id ?? '—' }}</td>
                            <td class="font-monospace small text-muted dc-font-code">{{ $log->ip_address ?? '0.0.0.0' }}</td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <button type="button" class="btn btn-sm btn-outline-info dc-btn-icon btn-ver-bitacora"
                                            data-bs-toggle="modal" data-bs-target="#modalDetalleBitacora"
                                            data-fecha="{{ $log->created_at ? $log->created_at->format('d/m/Y H:i:s') : 'N/A' }}"
                                            data-usuario="{{ $log->usuario_nombre ?? 'Sistema' }} (ID: {{ $log->usuario_id ?? 'N/A' }})"
                                            data-modulo="{{ strtoupper($log->modulo) }}"
                                            data-evento="{{ ucfirst($log->evento) }}"
                                            data-registro="{{ $log->registro_id ?? 'N/A' }}"
                                            data-ip="{{ $log->ip_address ?? 'No registrada' }}"
                                            data-descripcion="{{ $log->descripcion }}"
                                            title="Inspeccionar Evento">
                                        <i class="bi bi-terminal fs-6"></i>
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

    <div class="modal fade dc-modal" id="modalDetalleBitacora" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header border-bottom border-secondary opacity-75">
                    <h5 class="modal-title outfit-semibold text-white">
                        <i class="bi bi-terminal text-cyan me-2"></i> Inspección de Evento NOC
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <span class="d-block text-muted text-uppercase tracking-wide dc-text-micro">Usuario Ejecutor</span>
                            <span id="log_usuario" class="text-white outfit-medium"></span>
                        </div>
                        <div class="col-md-6">
                            <span class="d-block text-muted text-uppercase tracking-wide dc-text-micro">Marca de Tiempo</span>
                            <span id="log_fecha" class="text-cyan font-monospace"></span>
                        </div>
                        <div class="col-md-4">
                            <span class="d-block text-muted text-uppercase tracking-wide dc-text-micro">Módulo</span>
                            <span id="log_modulo" class="text-white outfit-semibold"></span>
                        </div>
                        <div class="col-md-4">
                            <span class="d-block text-muted text-uppercase tracking-wide dc-text-micro">Acción / Evento</span>
                            <span id="log_evento" class="text-white outfit-medium"></span>
                        </div>
                        <div class="col-md-4">
                            <span class="d-block text-muted text-uppercase tracking-wide dc-text-micro">Dirección IP / Registro</span>
                            <span class="text-muted font-monospace"><span id="log_ip"></span> [ID: <span id="log_registro" class="text-white"></span>]</span>
                        </div>
                    </div>
                    <div class="border-top border-secondary opacity-25 my-3"></div>
                    <div>
                        <span class="d-block text-muted text-uppercase tracking-wide dc-text-micro mb-2">Payload / Descripción de Auditoría</span>
                        <div class="p-3 rounded bg-dark border border-secondary border-opacity-25 font-monospace small text-light" id="log_descripcion"></div>
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
    <script src="{{ asset('js/bitacoras-ajax-script.js') }}"></script>
@endpush
