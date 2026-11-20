@extends('layouts.guest')

@section('titulo', 'Nueva Contraseña | IT-Secure')

@section('contenido')
    <div class="card shadow-lg w-100 dc-card-auth">
        <div class="card-body p-4 p-sm-5">

            <div class="text-center mb-5">
                <div class="avatar-shield mb-4 mx-auto dc-avatar-xl">
                    <i class="bi bi-shield-lock-fill text-cyan"></i>
                </div>
                <h4 class="text-white outfit-bold tracking-wide mb-2">Nueva Contraseña</h4>
                <p class="text-muted outfit-medium">Ingresa tu nueva clave de acceso para el sistema.</p>
            </div>

            <form action="{{ route('password.update') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="mb-4">
                    <label for="email" class="form-label text-light outfit-medium tracking-wide dc-text-sm">Correo Electrónico</label>
                    <div class="input-group">
                        <span class="input-group-text dc-input-addon">
                            <i class="bi bi-envelope"></i>
                        </span>
                        <input type="email" class="form-control dc-input-readonly outfit-regular" id="email" name="email" value="{{ $email ?? old('email') }}" readonly required>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label text-light outfit-medium tracking-wide dc-text-sm">Nueva Contraseña</label>
                    <div class="input-group">
                        <span class="input-group-text dc-input-addon">
                            <i class="bi bi-key-fill"></i>
                        </span>
                        <input type="password" class="form-control dc-input outfit-regular" id="password" name="password" placeholder="Mínimo 8 caracteres" required autofocus>
                        <button type="button" class="input-group-text dc-input-addon dc-toggle-password" data-target="#password" aria-label="Mostrar u ocultar nueva contraseña">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="mb-5">
                    <label for="password_confirmation" class="form-label text-light outfit-medium tracking-wide dc-text-sm">Confirmar Contraseña</label>
                    <div class="input-group">
                        <span class="input-group-text dc-input-addon">
                            <i class="bi bi-check-circle-fill"></i>
                        </span>
                        <input type="password" class="form-control dc-input outfit-regular" id="password_confirmation" name="password_confirmation" placeholder="Repite la contraseña" required>
                        <button type="button" class="input-group-text dc-input-addon dc-toggle-password" data-target="#password_confirmation" aria-label="Mostrar u ocultar confirmación de contraseña">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="d-grid mt-3">
                    <button type="submit" class="btn text-white py-3 dc-btn-gradient outfit-bold tracking-wide rounded-3">
                        <i class="bi bi-save-fill me-2 fs-5 align-middle"></i> Guardar Contraseña
                    </button>
                </div>
            </form>

        </div>
    </div>
@endsection
