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

            <button type="button" class="btn dc-btn-primary" data-bs-toggle="modal" data-bs-target="#modalCrearEquipo">
                <i class="bi bi-plus-lg me-1"></i> Nuevo Equipo
            </button>
        </div>
    </div>

    @if($centros->isNotEmpty())
        <div class="row g-3 mb-4">
            @foreach($centros as $c)
                @php
                    $conteoPorTipo = $c->equipos ? $c->equipos->groupBy('tipo')->map->count() : collect();
                @endphp
                <div class="col-12 col-md-6 col-xl-4">
                    <div class="dc-card p-3 h-100 dc-card-hover">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <span class="fw-bold text-cyan font-monospace dc-font-code">{{ $c->codigo }}</span>
                                <h6 class="text-white outfit-medium mb-0">{{ $c->nombre }}</h6>
                                <small class="text-muted dc-text-micro">{{ $c->sede->nombre ?? '' }} ({{ $c->sede->empresa->nombre ?? '' }})</small>
                            </div>
                            <a href="{{ route('centros.reporte.pdf', $c->id) }}" target="_blank" class="btn btn-sm btn-outline-info dc-btn-icon" title="Generar Informe PDF de Estado">
                                <i class="bi bi-file-earmark-pdf-fill fs-6"></i>
                            </a>
                        </div>
                        <div class="d-flex flex-wrap gap-1 mt-2">
                            @forelse($conteoPorTipo as $tipo => $cant)
                                <span class="badge bg-dark border border-secondary text-light outfit-regular dc-text-micro">
                                    <span class="text-cyan outfit-medium">{{ ucfirst($tipo) }}:</span> {{ $cant }}
                                </span>
                            @empty
                                <small class="text-muted dc-text-micro fst-italic">Sin hardware registrado</small>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

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
                        <th class="text-center dc-th-sm">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($equipos as $eq)
                        <tr class="transition-all dc-table-row">
                            <td class="text-muted outfit-light">{{ $eq->id }}</td>
                            <td><span class="fw-bold text-cyan font-monospace dc-font-code">{{ $eq->codigo_inventario }}</span></td>
                            <td><span class="text-light outfit-medium">{{ ucfirst($eq->tipo) }}</span></td>
                            <td class="text-light outfit-regular">{{ $eq->marca }} {{ $eq->modelo }}</td>
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
                            <td class="text-nowrap">
                                <div class="d-flex justify-content-center align-items-center gap-2">
                                    <a href="{{ route('equipos.ficha.pdf', $eq->id) }}" target="_blank" class="btn btn-sm btn-outline-info dc-btn-icon" title="Descargar Ficha Técnica">
                                        <i class="bi bi-file-earmark-pdf-fill fs-6"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-light dc-btn-icon btn-ver-qr"
                                            data-bs-toggle="modal" data-bs-target="#modalQrEquipo"
                                            data-id="{{ $eq->id }}"
                                            data-codigo="{{ $eq->codigo_inventario }}"
                                            data-tipo="{{ ucfirst($eq->tipo) }}"
                                            data-marca="{{ $eq->marca ?? '' }}"
                                            data-modelo="{{ $eq->modelo ?? '' }}"
                                            data-serie="{{ $eq->numero_serie ?? 'S/N' }}"
                                            data-centro="{{ $eq->centroDeComputo->nombre ?? 'Sin Asignar' }}"
                                            data-sede="{{ $eq->centroDeComputo->sede->nombre ?? '' }}"
                                            data-url="{{ route('equipos.ficha.pdf', $eq->id) }}"
                                            title="Generar Etiqueta QR del Activo">
                                        <i class="bi bi-qr-code fs-6"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-warning dc-btn-icon btn-editar"
                                            data-bs-toggle="modal" data-bs-target="#modalEditarEquipo"
                                            data-id="{{ $eq->id }}"
                                            data-codigo="{{ $eq->codigo_inventario }}"
                                            data-tipo="{{ $eq->tipo }}"
                                            data-marca="{{ $eq->marca }}"
                                            data-modelo="{{ $eq->modelo }}"
                                            data-serie="{{ $eq->numero_serie }}"
                                            data-estado="{{ $eq->estado }}"
                                            data-frecuencia="{{ $eq->dias_frecuencia_mantenimiento }}"
                                            data-centro="{{ $eq->centros_de_computo_id }}"
                                            title="Editar Equipo">
                                        <i class="bi bi-pencil-square fs-6"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger dc-btn-icon btn-eliminar"
                                            data-bs-toggle="modal" data-bs-target="#modalEliminarEquipo"
                                            data-id="{{ $eq->id }}"
                                            data-codigo="{{ $eq->codigo_inventario }}"
                                            title="Eliminar Equipo">
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

    <!-- Modal Crear Equipo -->
    <div class="modal fade dc-modal" id="modalCrearEquipo" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header border-bottom border-secondary opacity-75">
                    <h5 class="modal-title outfit-semibold text-white"><i class="bi bi-pc-display text-cyan me-2"></i> Registrar Nuevo Equipo</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('equipos.insertar') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted text-uppercase tracking-wide outfit-medium dc-text-micro">Código de Inventario</label>
                                <input type="text" name="codigo_inventario" class="form-control dc-input font-monospace" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted text-uppercase tracking-wide outfit-medium dc-text-micro">Tipo de Equipo</label>
                                <select name="tipo" class="form-select dc-input" required>
                                    <option value="" disabled selected>Seleccione...</option>
                                    <option value="desktop">Desktop</option>
                                    <option value="laptop">Laptop</option>
                                    <option value="servidor">Servidor</option>
                                    <option value="router">Router</option>
                                    <option value="switch">Switch</option>
                                    <option value="impresora">Impresora</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted text-uppercase tracking-wide outfit-medium dc-text-micro">Marca</label>
                                <input type="text" name="marca" class="form-control dc-input" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted text-uppercase tracking-wide outfit-medium dc-text-micro">Modelo</label>
                                <input type="text" name="modelo" class="form-control dc-input" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted text-uppercase tracking-wide outfit-medium dc-text-micro">Número de Serie</label>
                                <input type="text" name="numero_serie" class="form-control dc-input font-monospace">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted text-uppercase tracking-wide outfit-medium dc-text-micro">Estado</label>
                                <select name="estado" class="form-select dc-input" required>
                                    <option value="operativo">Operativo</option>
                                    <option value="mantenimiento">En Mantenimiento</option>
                                    <option value="averiado">Averiado</option>
                                    <option value="baja_definitiva">Baja Definitiva</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted text-uppercase tracking-wide outfit-medium dc-text-micro">Frecuencia de Mantenimiento (Días)</label>
                                <input type="number" name="dias_frecuencia_mantenimiento" class="form-control dc-input" min="1" value="180" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted text-uppercase tracking-wide outfit-medium dc-text-micro">Centro de Cómputo</label>
                                <select name="centros_de_computo_id" class="form-select dc-input" required>
                                    <option value="" disabled selected>Seleccione Ubicación...</option>
                                    @foreach($centros ?? [] as $centro)
                                        <option value="{{ $centro->id }}">
                                            [{{ $centro->codigo }}] {{ $centro->nombre }} ({{ $centro->sede->nombre ?? '' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top border-secondary opacity-75">
                        <button type="button" class="btn dc-btn-outline px-4" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn dc-btn-primary px-4">Guardar Equipo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Editar Equipo -->
    <div class="modal fade dc-modal" id="modalEditarEquipo" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header border-bottom border-secondary opacity-75">
                    <h5 class="modal-title outfit-semibold text-white"><i class="bi bi-pencil-square text-cyan me-2"></i> Editar Equipo <span id="editTitleCode" class="text-cyan font-monospace"></span></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formEditarEquipo" action="#" method="POST" data-action-template="{{ route('equipos.actualizar', ['equipo' => '__ID__']) }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted text-uppercase tracking-wide outfit-medium dc-text-micro">Código de Inventario</label>
                                <input type="text" name="codigo_inventario" id="edit_codigo_inventario" class="form-control dc-input font-monospace" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted text-uppercase tracking-wide outfit-medium dc-text-micro">Tipo de Equipo</label>
                                <select name="tipo" id="edit_tipo" class="form-select dc-input" required>
                                    <option value="desktop">Desktop</option>
                                    <option value="laptop">Laptop</option>
                                    <option value="servidor">Servidor</option>
                                    <option value="router">Router</option>
                                    <option value="switch">Switch</option>
                                    <option value="impresora">Impresora</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted text-uppercase tracking-wide outfit-medium dc-text-micro">Marca</label>
                                <input type="text" name="marca" id="edit_marca" class="form-control dc-input" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted text-uppercase tracking-wide outfit-medium dc-text-micro">Modelo</label>
                                <input type="text" name="modelo" id="edit_modelo" class="form-control dc-input" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted text-uppercase tracking-wide outfit-medium dc-text-micro">Número de Serie</label>
                                <input type="text" name="numero_serie" id="edit_numero_serie" class="form-control dc-input font-monospace">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted text-uppercase tracking-wide outfit-medium dc-text-micro">Estado</label>
                                <select name="estado" id="edit_estado" class="form-select dc-input" required>
                                    <option value="operativo">Operativo</option>
                                    <option value="mantenimiento">En Mantenimiento</option>
                                    <option value="averiado">Averiado</option>
                                    <option value="baja_definitiva">Baja Definitiva</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted text-uppercase tracking-wide outfit-medium dc-text-micro">Frecuencia de Mantenimiento (Días)</label>
                                <input type="number" name="dias_frecuencia_mantenimiento" id="edit_dias_frecuencia_mantenimiento" class="form-control dc-input" min="1" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted text-uppercase tracking-wide outfit-medium dc-text-micro">Centro de Cómputo</label>
                                <select name="centros_de_computo_id" id="edit_centros_de_computo_id" class="form-select dc-input" required>
                                    <option value="" disabled>Seleccione Ubicación...</option>
                                    @foreach($centros ?? [] as $centro)
                                        <option value="{{ $centro->id }}">
                                            [{{ $centro->codigo }}] {{ $centro->nombre }} ({{ $centro->sede->nombre ?? '' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top border-secondary opacity-75">
                        <button type="button" class="btn dc-btn-outline px-4" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn dc-btn-primary px-4">Actualizar Equipo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Eliminar Equipo -->
    <div class="modal fade dc-modal dc-modal-danger" id="modalEliminarEquipo" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body p-4 text-center">
                    <div class="mb-4 mt-2">
                        <i class="bi bi-exclamation-triangle text-danger dc-text-huge"></i>
                    </div>
                    <h5 class="text-white outfit-semibold mb-3">¿Eliminar Equipo?</h5>
                    <p class="text-muted mb-4">¿Estás seguro que deseas dar de baja el equipo con código <strong id="deleteCode" class="text-white font-monospace"></strong>?</p>
                    <form id="formEliminarEquipo" action="#" method="POST" data-action-template="{{ route('equipos.eliminar', ['equipo' => '__ID__']) }}">
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

    <!-- Modal Etiqueta QR del Activo -->
    <div class="modal fade dc-modal" id="modalQrEquipo" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-bottom border-secondary opacity-75">
                    <h5 class="modal-title outfit-semibold text-white">
                        <i class="bi bi-qr-code-scan text-cyan me-2"></i> Etiqueta de Identificación de Activo
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <div id="printAssetTag" class="dc-asset-sticker mx-auto p-3 rounded text-start">
                        <div class="d-flex justify-content-between align-items-center border-bottom border-secondary pb-2 mb-3">
                            <span class="outfit-bold text-white fs-6">IT-<span class="text-cyan">SECURE</span></span>
                            <span class="badge dc-badge-secondary dc-text-micro font-monospace">ACTIVO TI</span>
                        </div>
                        <div class="row align-items-center g-3">
                            <div class="col-7">
                                <span class="d-block dc-text-micro text-muted text-uppercase tracking-wide">Código Inventario</span>
                                <h5 id="qr_codigo" class="text-cyan font-monospace outfit-bold mb-2"></h5>

                                <span class="d-block dc-text-micro text-muted text-uppercase tracking-wide">Hardware</span>
                                <span id="qr_equipo" class="text-white outfit-medium d-block small mb-1"></span>

                                <span class="d-block dc-text-micro text-muted text-uppercase tracking-wide">Serie / Ubicación</span>
                                <small class="text-muted font-monospace d-block" id="qr_serie"></small>
                                <small class="text-light outfit-regular d-block" id="qr_ubicacion"></small>
                            </div>
                            <div class="col-5 text-center">
                                <div id="qrCanvasContainer" class="bg-white p-2 rounded d-inline-block shadow-sm"></div>
                                <small class="text-muted dc-text-micro d-block mt-1">Escanear para Ficha</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top border-secondary opacity-75 d-flex justify-content-between">
                    <button type="button" class="btn dc-btn-outline px-4" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn dc-btn-primary px-4" onclick="window.print()">
                        <i class="bi bi-printer me-1"></i> Imprimir Etiqueta
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script src="{{ asset('js/dataTable.js') }}"></script>
    <script src="{{ asset('js/equipos-ajax-script.js') }}"></script>
@endpush
