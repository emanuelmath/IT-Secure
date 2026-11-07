@extends('layouts.layout')

@section('titulo', 'Mi Perfil | IT-Secure')
@section('subtitulo', 'Ajustes de Cuenta')
@section('breadcrumb', 'Mi Perfil')

@section('contenido')
    <div class="row g-4">
        <div class="col-lg-4 col-xl-3">
            <div class="dc-card text-center py-5 px-4 h-100">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-4" style="width: 90px; height: 90px; background-color: rgba(56, 189, 248, 0.1); border: 1px solid rgba(56, 189, 248, 0.3); color: var(--dc-cyan); box-shadow: 0 0 20px var(--dc-glow);">
                    <i class="bi bi-person-bounding-box" style="font-size: 2.5rem;"></i>
                </div>

                <h5 class="outfit-semibold text-white mb-1">{{ auth()->user()->nombre ?? 'Administrador' }}</h5>
                @php
                    $roles = auth()->user()->obtenerRolesNombres();
                @endphp

                <p class="text-cyan outfit-medium mb-4"
                   style="font-size: 0.85rem; letter-spacing: 0.1em; text-transform: uppercase;">
                    {{ !empty($roles) ? implode(' | ', $roles) : 'Personal IT' }}
                </p>


                <div class="border-top border-secondary opacity-25 mb-4"></div>

                <div class="text-start">
                    <div class="mb-3">
                        <span class="d-block text-muted text-uppercase tracking-wide" style="font-size: 0.7rem;">Correo Electrónico</span>
                        <span class="text-white outfit-medium" style="font-size: 0.9rem;">{{ auth()->user()->email ?? 'usuario@it-secure.com' }}</span>
                    </div>
                    <div class="mb-3">
                        <span class="d-block text-muted text-uppercase tracking-wide" style="font-size: 0.7rem;">Estado de Cuenta</span>
                        <span class="badge dc-badge-success outfit-medium mt-1">Activa</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8 col-xl-9">
            <div class="dc-card h-100">
                <div class="dc-card-title d-flex align-items-center gap-2">
                    <i class="bi bi-shield-lock text-cyan"></i> Seguridad y Credenciales
                </div>

                <div class="p-4 p-md-5">
                    <form action="{{ route('password.update-profile', auth()->id())}}" method="POST">
                        @csrf
                        @method('POST')

                        <div class="row mb-4">
                            <div class="col-12">
                                <p class="text-muted outfit-light mb-4" style="font-size: 0.9rem;">
                                    Actualiza tu contraseña de acceso al sistema. Por seguridad, te recomendamos utilizar una combinación de letras, números y símbolos especiales.
                                </p>
                            </div>
                        </div>

                        <div class="row g-4 mb-5">
                            <div class="col-md-12">
                                <label class="form-label text-muted text-uppercase tracking-wide outfit-medium" style="font-size: 0.75rem;">Contraseña Actual</label>
                                <div class="input-group">
                                    <span class="input-group-text dc-input-addon"><i class="bi bi-key"></i></span>
                                    <input type="password" name="contrasenha" class="form-control dc-input @error('contrasenha') is-invalid @enderror" placeholder="Ingresa tu contraseña actual" required>
                                </div>
                                @error('contrasenha')
                                <div class="text-danger mt-1" style="font-size: 0.8rem;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-muted text-uppercase tracking-wide outfit-medium" style="font-size: 0.75rem;">Nueva Contraseña</label>
                                <div class="input-group">
                                    <span class="input-group-text dc-input-addon"><i class="bi bi-shield-plus"></i></span>
                                    <input type="password" name="contrasenhaNueva" class="form-control dc-input @error('contrasenhaNueva') is-invalid @enderror" placeholder="Nueva credencial" required>
                                </div>
                                @error('contrasenhaNueva')
                                <div class="text-danger mt-1" style="font-size: 0.8rem;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-muted text-uppercase tracking-wide outfit-medium" style="font-size: 0.75rem;">Confirmar Contraseña</label>
                                <div class="input-group">
                                    <span class="input-group-text dc-input-addon"><i class="bi bi-shield-check"></i></span>
                                    <input type="password" name="contrasenhaConfirmar" class="form-control dc-input" placeholder="Repite la nueva credencial" required>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-3 border-top border-secondary opacity-75 pt-4">
                            <button type="reset" class="btn dc-btn-outline px-4 outfit-medium">Descartar</button>
                            <button type="submit" class="btn dc-btn-primary px-4 outfit-medium d-flex align-items-center gap-2">
                                <i class="bi bi-check2-circle"></i> Actualizar Credenciales
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
