@extends('layouts.layout')

@section('titulo', 'Gestión de Usuarios | IT-Secure')
@section('subtitulo', 'Administración de Cuentas y Roles')
@section('breadcrumb', 'Usuarios')

@push('estilos')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
@endpush

@section('contenido')
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h1 class="h4 mb-0 text-light outfit-semibold"><i class="bi bi-people-fill text-cyan me-2 opacity-75"></i> Directorio de Usuarios</h1>
        <button type="button" id="btnNuevoUsuario" class="btn dc-btn-primary" data-bs-toggle="modal" data-bs-target="#modalUsuario">
            <i class="bi bi-person-plus-fill me-1"></i> Nuevo Usuario
        </button>
    </div>

    <div class="dc-card">
        <div class="card-body p-0">
            <div class="table-responsive p-4">
                <table id="tbl" class="table table-hover align-middle w-100 dc-table-dark">
                    <thead>
                    <tr>
                        <th class="dc-th-xs">#</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Roles</th>
                        <th>Centros Asignados</th>
                        <th>Estado</th>
                        <th class="text-center dc-th-md">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($usuarios as $u)
                        <tr class="transition-all dc-table-row">
                            <td class="text-muted outfit-light">{{ $u->id }}</td>
                            <td><span class="text-light outfit-semibold">{{ $u->nombre }}</span></td>
                            <td class="text-muted outfit-regular">{{ $u->email }}</td>
                            <td>
                                @foreach ($u->roles as $rol)
                                    <span class="outfit-medium text-cyan">{{ $rol->nombre }}</span>
                                    <br>
                                    <small class="text-muted d-block dc-text-micro">{{ $rol->descripcion }}</small>
                                @endforeach
                            </td>
                            <td>
                                @if($u->roles->contains('nombre', 'Administrador'))
                                    <span class="badge dc-badge-secondary rounded-pill px-3 outfit-medium">Acceso Global</span>
                                @elseif($u->centrosDeComputo->isNotEmpty())
                                    @foreach($u->centrosDeComputo as $cc)
                                        <span class="badge bg-dark border border-secondary text-light outfit-regular mb-1">
                                            {{ $cc->codigo }} — {{ $cc->nombre }}
                                        </span>
                                    @endforeach
                                @else
                                    <span class="text-muted small fst-italic">Sin centros asignados</span>
                                @endif
                            </td>
                            <td>
                                @if($u->activo)
                                    <span class="badge dc-badge-success rounded-pill px-3 outfit-medium">Activo</span>
                                @else
                                    <span class="badge dc-badge-danger rounded-pill px-3 outfit-medium">Inactivo</span>
                                @endif
                            </td>
                            <td class="text-center text-nowrap">
                                <div class="d-flex justify-content-center align-items-center gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-info dc-btn-icon" data-bs-toggle="modal" data-bs-target="#modalEditarUsuario" onclick="editarUsuario({{ $u->id }})" title="Editar Usuario">
                                        <i class="bi bi-pencil-square fs-6"></i>
                                    </button>
                                    @if($u->activo)
                                        <button type="button" class="btn btn-sm btn-outline-danger dc-btn-icon" data-bs-toggle="modal" data-bs-target="#modalEliminarUsuario" onclick="eliminarUsuario({{ $u->id }}, '{{ $u->nombre }}')" title="Desactivar">
                                            <i class="bi bi-trash-fill fs-6"></i>
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-sm btn-outline-success dc-btn-icon" data-bs-toggle="modal" data-bs-target="#modalActivarUsuario" onclick="activarUsuario({{ $u->id }}, '{{ $u->nombre }}')" title="Reactivar">
                                            <i class="bi bi-check-circle-fill fs-6"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Crear Usuario -->
    <div class="modal fade" id="modalUsuario" tabindex="-1" aria-labelledby="modalUsuarioLabel" aria-hidden="true" data-bs-theme="dark">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content dc-modal shadow-lg">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title text-light outfit-bold" id="modalUsuarioLabel">
                        <i class="bi bi-person-plus-fill text-cyan me-2 opacity-75"></i> Registrar Usuario
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="formUsuario" action="{{ route('usuarios.insertar') }}" method="POST">
                    @csrf
                    <div class="modal-body pt-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nombre" class="form-label text-muted small outfit-bold text-uppercase tracking-wide">Nombre Completo</label>
                                <div class="input-group">
                                    <span class="input-group-text dc-input-addon"><i class="bi bi-person"></i></span>
                                    <input type="text" class="form-control dc-input outfit-regular" id="nombre" name="nombre" required maxlength="255" autocomplete="name" placeholder="Ej. Juan Pérez">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label text-muted small outfit-bold text-uppercase tracking-wide">Correo Electrónico</label>
                                <div class="input-group">
                                    <span class="input-group-text dc-input-addon"><i class="bi bi-envelope"></i></span>
                                    <input type="email" class="form-control dc-input outfit-regular" id="email" name="email" required maxlength="255" autocomplete="email" placeholder="usuario@itsecure.sv">
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="contrasenha" class="form-label text-muted small outfit-bold text-uppercase tracking-wide">Contraseña de Acceso</label>
                                <div class="input-group">
                                    <span class="input-group-text dc-input-addon"><i class="bi bi-key"></i></span>
                                    <input type="password" class="form-control dc-input outfit-regular" id="contrasenha" name="contrasenha" required minlength="8" placeholder="••••••••">
                                </div>
                                <div class="form-text text-muted dc-text-micro">Debe contener al menos 8 caracteres.</div>
                            </div>

                            @if (isset($roles) && count($roles) > 0)
                                <div class="col-md-5">
                                    <label class="form-label text-muted small outfit-bold text-uppercase tracking-wide">Asignar Roles</label>
                                    <div class="dc-checkbox-list">
                                        @foreach ($roles as $rol)
                                            <label class="dc-checkbox-item" for="crear_rol_{{ $rol->id }}">
                                                <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $rol->id }}" id="crear_rol_{{ $rol->id }}">
                                                <div>
                                                    <span class="d-block text-light outfit-medium small">{{ $rol->nombre }}</span>
                                                    @if($rol->descripcion)
                                                        <span class="d-block text-muted dc-text-micro">{{ $rol->descripcion }}</span>
                                                    @endif
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if (isset($centros) && count($centros) > 0)
                                <div class="col-md-7">
                                    <label class="form-label text-muted small outfit-bold text-uppercase tracking-wide">Centros de Cómputo a Cargo</label>
                                    <div class="dc-checkbox-list">
                                        @foreach ($centros as $centro)
                                            <label class="dc-checkbox-item" for="crear_centro_{{ $centro->id }}">
                                                <input class="form-check-input" type="checkbox" name="centros[]" value="{{ $centro->id }}" id="crear_centro_{{ $centro->id }}">
                                                <div>
                                                    <span class="d-block text-light outfit-medium small">
                                                        <span class="text-cyan font-monospace">{{ $centro->codigo }}</span> — {{ $centro->nombre }}
                                                    </span>
                                                    <span class="d-block text-muted dc-text-micro">{{ $centro->sede->nombre ?? 'Sin sede' }}</span>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                    <div class="form-text text-muted dc-text-micro">Obligatorio para Supervisores y Técnicos.</div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 pt-2">
                        <button type="button" class="btn btn-outline-secondary dc-btn-outline" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn dc-btn-primary">
                            <i class="bi bi-save2 me-1"></i> Guardar Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Editar Usuario -->
    <div class="modal fade" id="modalEditarUsuario" tabindex="-1" aria-labelledby="modalEditarUsuarioLabel" aria-hidden="true" data-bs-theme="dark">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content dc-modal shadow-lg">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title text-light outfit-bold" id="modalEditarUsuarioLabel">
                        <i class="bi bi-pencil-square text-cyan me-2 opacity-75"></i> Editar Usuario
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="formEditar" method="POST" data-action-template="{{ route('usuarios.actualizar', ['usuario' => '__ID__']) }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body pt-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="editar_nombre" class="form-label text-muted small outfit-bold text-uppercase tracking-wide">Nombre Completo</label>
                                <div class="input-group">
                                    <span class="input-group-text dc-input-addon"><i class="bi bi-person"></i></span>
                                    <input type="text" class="form-control dc-input outfit-regular" id="editar_nombre" name="nombre" required maxlength="255">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="editar_email" class="form-label text-muted small outfit-bold text-uppercase tracking-wide">Correo Electrónico</label>
                                <div class="input-group">
                                    <span class="input-group-text dc-input-addon"><i class="bi bi-envelope"></i></span>
                                    <input type="email" class="form-control dc-input outfit-regular" id="editar_email" name="email" required maxlength="255">
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="editar_contrasenha" class="form-label text-muted small outfit-bold text-uppercase tracking-wide">Nueva Contraseña (Opcional)</label>
                                <div class="input-group">
                                    <span class="input-group-text dc-input-addon"><i class="bi bi-key"></i></span>
                                    <input type="password" class="form-control dc-input outfit-regular" id="editar_contrasenha" name="contrasenha" minlength="8" placeholder="••••••••">
                                </div>
                                <div class="form-text text-muted dc-text-micro">Dejar en blanco si no desea cambiarla.</div>
                            </div>

                            @if (isset($roles) && count($roles) > 0)
                                <div class="col-md-5">
                                    <label class="form-label text-muted small outfit-bold text-uppercase tracking-wide">Asignar Roles</label>
                                    <div class="dc-checkbox-list">
                                        @foreach ($roles as $rol)
                                            <label class="dc-checkbox-item" for="editar_rol_{{ $rol->id }}">
                                                <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $rol->id }}" id="editar_rol_{{ $rol->id }}">
                                                <div>
                                                    <span class="d-block text-light outfit-medium small">{{ $rol->nombre }}</span>
                                                    @if($rol->descripcion)
                                                        <span class="d-block text-muted dc-text-micro">{{ $rol->descripcion }}</span>
                                                    @endif
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if (isset($centros) && count($centros) > 0)
                                <div class="col-md-7">
                                    <label class="form-label text-muted small outfit-bold text-uppercase tracking-wide">Centros de Cómputo a Cargo</label>
                                    <div class="dc-checkbox-list">
                                        @foreach ($centros as $centro)
                                            <label class="dc-checkbox-item" for="editar_centro_{{ $centro->id }}">
                                                <input class="form-check-input" type="checkbox" name="centros[]" value="{{ $centro->id }}" id="editar_centro_{{ $centro->id }}">
                                                <div>
                                                    <span class="d-block text-light outfit-medium small">
                                                        <span class="text-cyan font-monospace">{{ $centro->codigo }}</span> — {{ $centro->nombre }}
                                                    </span>
                                                    <span class="d-block text-muted dc-text-micro">{{ $centro->sede->nombre ?? 'Sin sede' }}</span>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 pt-2">
                        <button type="button" class="btn btn-outline-secondary dc-btn-outline" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn dc-btn-primary">
                            <i class="bi bi-arrow-repeat me-1"></i> Actualizar Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Eliminar Usuario -->
    <div class="modal fade" id="modalEliminarUsuario" tabindex="-1" aria-labelledby="modalEliminarUsuarioLabel" aria-hidden="true" data-bs-theme="dark">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content dc-modal dc-modal-danger shadow-lg">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title text-danger outfit-bold" id="modalEliminarUsuarioLabel">
                        <i class="bi bi-exclamation-triangle-fill me-2 opacity-75"></i> Confirmar Inhabilitación
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formEliminar" method="POST" data-action-template="{{ route('usuarios.eliminar', ['usuario' => '__ID__']) }}">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body text-light pt-4 outfit-regular">
                        ¿Estás seguro de que deseas inhabilitar al usuario <strong id="nombreEliminar" class="text-danger outfit-semibold"></strong>?
                        <p class="text-muted small mt-2 mb-0">El usuario no será eliminado físicamente, pero perderá el acceso al sistema (Soft Delete lógico).</p>
                    </div>
                    <div class="modal-footer border-top-0 pt-0 mt-3">
                        <button type="button" class="btn btn-outline-secondary dc-btn-outline" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger dc-btn-danger">Inhabilitar Acceso</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Activar Usuario -->
    <div class="modal fade" id="modalActivarUsuario" tabindex="-1" aria-labelledby="modalActivarUsuarioLabel" aria-hidden="true" data-bs-theme="dark">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content dc-modal dc-modal-success shadow-lg">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title text-success outfit-bold" id="modalActivarUsuarioLabel">
                        <i class="bi bi-check-circle-fill me-2 opacity-75"></i> Confirmar Activación
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formActivar" method="POST" data-action-template="{{ route('usuarios.activar', ['usuario' => '__ID__']) }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body text-light pt-4 outfit-regular">
                        ¿Estás seguro de que deseas reactivar al usuario <strong id="nombreActivar" class="text-success outfit-semibold"></strong>?
                        <p class="text-muted small mt-2 mb-0">El usuario recuperará inmediatamente el acceso al sistema con sus roles asignados.</p>
                    </div>
                    <div class="modal-footer border-top-0 pt-0 mt-3">
                        <button type="button" class="btn btn-outline-secondary dc-btn-outline" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success dc-btn-success">Activar Acceso</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
    <script src="{{ asset('js/dataTable.js') }}"></script>
    <script src="{{ asset('js/usuarios-ajax-script.js') }}"></script>
@endpush
