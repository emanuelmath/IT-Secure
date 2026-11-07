@extends('layouts.guest')

@section('titulo', 'Iniciar Sesión | IT-Secure')

@section('contenido')
    <div class="card shadow-lg w-100 dc-card-auth">
        <div class="card-body p-4 p-sm-5">

            <div class="text-center mb-5">
                <div class="avatar-shield mb-4 mx-auto dc-avatar-xl">
                    <i class="bi bi-shield-lock text-cyan"></i>
                </div>
                <h4 class="text-white outfit-bold tracking-wide mb-2">Acceso al Sistema</h4>
                <p class="text-muted outfit-medium">Ingresa tus credenciales para continuar</p>
            </div>

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="email" class="form-label text-light outfit-medium tracking-wide dc-text-sm">Correo Electrónico</label>
                    <div class="input-group">
                        <span class="input-group-text dc-input-addon">
                            <i class="bi bi-envelope"></i>
                        </span>
                        <input type="email" class="form-control dc-input outfit-regular" id="email" name="email" placeholder="user@itsecure.sv" required autofocus>
                    </div>
                </div>

                <div class="mb-5">
                    <div class="d-flex justify-content-between mb-2">
                        <label for="password" class="form-label text-light outfit-medium tracking-wide dc-text-sm mb-0">Contraseña</label>
                        <a href="{{ route('password.request') }}" class="text-cyan text-decoration-none outfit-medium dc-text-sm dc-hover-white transition-all">¿Olvidaste tu clave?</a>
                    </div>
                    <div class="input-group">
                        <span class="input-group-text dc-input-addon">
                            <i class="bi bi-key"></i>
                        </span>
                        <input type="password" class="form-control dc-input outfit-regular" id="password" name="contrasenha" placeholder="••••••••" required>
                    </div>
                </div>

                <div class="d-grid mt-3">
                    <button type="submit" class="btn text-white py-3 dc-btn-gradient outfit-bold tracking-wide rounded-3">
                        <i class="bi bi-box-arrow-in-right me-2 fs-5 align-middle"></i> Iniciar Sesión
                    </button>
                </div>
            </form>

        </div>
    </div>
@endsection
