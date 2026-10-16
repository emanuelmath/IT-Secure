@extends('layouts.layout')

@section('titulo', 'Usuarios')

@push('estilos')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
@endpush

@section('contenido')
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1 class="h3 mb-0"><i class="bi bi-box-seam text-primary"></i> Usuarios </h1>
        <button type="button" id="btnNuevoUsuario" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalUsuario">
            <i class="bi bi-plus-lg"></i> Nuevo Usuario
        </button>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table id="tbl" class="table table-hover table-striped align-middle w-100">
                <thead class="table-dark">
                <tr>
                    <th style="width: 3rem;">#</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Roles</th>
                    <th>Activo</th>
                    <th class="text-center" style="width: 8rem;">Acciones</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($usuarios as $u)
                    <tr>
                        <td class="text-muted">{{ $u->id }}</td>
                        <td><span class="fw-semibold">{{ $u->nombre }}</span></td>
                        <td>{{ $u->email }}</td>
                        <td>
                            @foreach ($u->roles as $rol)
                                <span class="fw-semibold">{{ $rol->nombre }}</span>
                                <br>
                                <small class="text-muted d-block">{{ $rol->descripcion }}</small>
                            @endforeach
                        </td>
                        <td>{{ $u->activo ? "Sí" : "No" }}</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#modalEditarUsuario" onclick="editarUsuario({{ $u->id }})">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            @if($u->activo)
                                <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalEliminarUsuario" onclick="eliminarUsuario({{ $u->id }}, '{{$u->nombre }}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            @else
                                <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#modalActivarUsuario" onclick="activarUsuario({{ $u->id }}, '{{$u->nombre }}')">
                                    <i class="bi bi-check"></i>
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL REGISTRAR USUARIO -->
    <div class="modal fade" id="modalUsuario" tabindex="-1" aria-labelledby="modalUsuarioLabel" aria-hidden="true" data-bs-theme="dark">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content dc-modal shadow-lg">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title text-light" id="modalUsuarioLabel">
                        <i class="bi bi-person-plus-fill text-cyan me-2"></i> Registrar Usuario
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="formUsuario" action="{{ route('usuarios.insertar') }}" method="POST">
                    @csrf
                    <div class="modal-body">

                        <div class="mb-3">
                            <label for="nombre" class="form-label text-muted small fw-bold text-uppercase">Nombre Completo</label>
                            <div class="input-group">
                                <span class="input-group-text dc-addon"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control form-control-dc" id="nombre" name="nombre" required maxlength="255" autocomplete="name">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label text-muted small fw-bold text-uppercase">Correo Electrónico</label>
                            <div class="input-group">
                                <span class="input-group-text dc-addon"><i class="bi bi-envelope"></i></span>
                                <input type="email" class="form-control form-control-dc" id="email" name="email" required maxlength="255" autocomplete="email">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="contrasenha" class="form-label text-muted small fw-bold text-uppercase">Contraseña de Acceso</label>
                            <div class="input-group">
                                <span class="input-group-text dc-addon"><i class="bi bi-key"></i></span>
                                <input type="password" class="form-control form-control-dc" id="contrasenha" name="contrasenha" required minlength="8">
                            </div>
                            <div class="form-text text-muted" style="font-size: 0.75rem;">Debe contener al menos 8 caracteres.</div>
                        </div>

                        @if (isset($roles) && count($roles) > 0)
                            <div class="mb-2">
                                <label for="roles" class="form-label text-muted small fw-bold text-uppercase">Asignar Roles</label>
                                <select class="form-select form-select-dc" id="roles" name="roles[]" multiple required style="height: 100px;">
                                    @foreach ($roles as $rol)
                                        <option value="{{ $rol->id }}">{{ $rol->nombre }}</option>
                                    @endforeach
                                </select>
                                <div class="form-text text-muted" style="font-size: 0.75rem;">Usa Ctrl (o Cmd) para seleccionar múltiples roles.</div>
                            </div>
                        @endif

                    </div>
                    <div class="modal-footer border-secondary">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" style="background-color: var(--dc-blue-electric); border: none;">
                            <i class="bi bi-save2 me-1"></i> Guardar Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL EDITAR USUARIO -->
    <div class="modal fade" id="modalEditarUsuario" tabindex="-1" aria-labelledby="modalEditarUsuarioLabel" aria-hidden="true" data-bs-theme="dark">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content dc-modal shadow-lg">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title text-light" id="modalEditarUsuarioLabel">
                        <i class="bi bi-pencil-square text-cyan me-2"></i> Editar Usuario
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="formEditar" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">

                        <div class="mb-3">
                            <label for="editar_nombre" class="form-label text-muted small fw-bold text-uppercase">Nombre Completo</label>
                            <div class="input-group">
                                <span class="input-group-text dc-addon"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control form-control-dc" id="editar_nombre" name="nombre" required maxlength="255">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="editar_email" class="form-label text-muted small fw-bold text-uppercase">Correo Electrónico</label>
                            <div class="input-group">
                                <span class="input-group-text dc-addon"><i class="bi bi-envelope"></i></span>
                                <input type="email" class="form-control form-control-dc" id="editar_email" name="email" required maxlength="255">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="editar_contrasenha" class="form-label text-muted small fw-bold text-uppercase">Nueva Contraseña (Opcional)</label>
                            <div class="input-group">
                                <span class="input-group-text dc-addon"><i class="bi bi-key"></i></span>
                                <input type="password" class="form-control form-control-dc" id="editar_contrasenha" name="contrasenha" minlength="8">
                            </div>
                            <div class="form-text text-muted" style="font-size: 0.75rem;">Dejar en blanco si no desea cambiarla.</div>
                        </div>

                        @if (isset($roles) && count($roles) > 0)
                            <div class="mb-2">
                                <label for="editar_roles" class="form-label text-muted small fw-bold text-uppercase">Asignar Roles</label>
                                <select class="form-select form-select-dc" id="editar_roles" name="roles[]" multiple required style="height: 100px;">
                                    @foreach ($roles as $rol)
                                        <option value="{{ $rol->id }}">{{ $rol->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                    </div>
                    <div class="modal-footer border-secondary">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" style="background-color: var(--dc-blue-electric); border: none;">
                            <i class="bi bi-arrow-repeat me-1"></i> Actualizar Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL ELIMINAR USUARIO -->
    <div class="modal fade" id="modalEliminarUsuario" tabindex="-1" aria-labelledby="modalEliminarUsuarioLabel" aria-hidden="true" data-bs-theme="dark">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content dc-modal shadow-lg">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title text-danger" id="modalEliminarUsuarioLabel">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> Confirmar Eliminación
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formEliminar" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body text-light">
                        ¿Estás seguro de que deseas eliminar al usuario <strong id="nombreEliminar" class="text-cyan"></strong>?
                        <p class="text-muted small mt-2 mb-0">Esta acción hará que el usuario quede inhabilitado de sus funciones.</p>
                    </div>
                    <div class="modal-footer border-secondary">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalActivarUsuario" tabindex="-1" aria-labelledby="modalActivarUsuarioLabel" aria-hidden="true" data-bs-theme="dark">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content dc-modal shadow-lg">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title text-success" id="modalActivarUsuarioLabel">
                        <i class="bi bi-check me-2"></i> Confirmar Activación
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formActivar" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body text-light">
                        ¿Estás seguro de que deseas activar al usuario <strong id="nombreActivar" class="text-success-emphasis"></strong>?
                        <p class="text-muted small mt-2 mb-0">Esta acción reactivará todas las funciones que el usuario puede hacer.</p>
                    </div>
                    <div class="modal-footer border-secondary">
                        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Activar</button>
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
    <script>
        function editarUsuario(id) {
            const urlBase = "{{ route('usuarios.actualizar', ['usuario' => '__ID__']) }}";

            $.ajax({
                url: `/usuarios/${id}/buscar`,
                method: 'GET',
                dataType: 'json'
            })
                .done(function (usuario) {
                    $('#formEditar')[0].reset();
                    $('#editar_nombre').val(usuario.nombre ?? '');
                    $('#editar_email').val(usuario.email ?? '');

                    // Mapear y seleccionar roles si la relación viene en el JSON
                    $('#editar_roles').val([]);
                    if (usuario.roles && usuario.roles.length > 0) {
                        const rolesIds = usuario.roles.map(rol => rol.id);
                        $('#editar_roles').val(rolesIds);
                    }

                    $('#formEditar').attr('action', urlBase.replace('__ID__', usuario.id));
                })
                .fail(function () {
                    alert('Error al consultar el usuario.');
                });
        }

        function eliminarUsuario(id, nombre) {
            const urlBase = "{{ route('usuarios.eliminar', ['usuario' => '__ID__']) }}";
            $('#nombreEliminar').html(nombre);
            $('#formEliminar').attr('action', urlBase.replace('__ID__', id));
        }

        function activarUsuario(id, nombre) {
            const urlBase = "{{ route('usuarios.activar', ['usuario' => '__ID__']) }}";
            $('#nombreActivar').html(nombre);
            $('#formActivar').attr('action', urlBase.replace('__ID__', id));
        }
    </script>
@endpush
