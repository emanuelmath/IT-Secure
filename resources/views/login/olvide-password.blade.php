@extends('layouts.guest')

@section('titulo', 'Recuperar Contraseña | IT-Secure')

@section('contenido')
    <div class="card shadow-lg w-100 dc-card-auth">
        <div class="card-body p-4 p-sm-5">

            <div class="text-center mb-5">
                <div class="avatar-shield mb-4 mx-auto dc-avatar-xl">
                    <i class="bi bi-unlock-fill text-cyan"></i>
                </div>
                <h4 class="text-white outfit-bold tracking-wide mb-2">Recuperar Acceso</h4>
                <p class="text-muted outfit-medium">Ingresa tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña.</p>
            </div>

            <form action="{{ route('password.email') }}" method="POST">
                @csrf
                <div class="mb-5">
                    <label for="email" class="form-label text-light outfit-medium tracking-wide dc-text-sm">Correo Electrónico</label>
                    <div class="input-group">
                        <span class="input-group-text dc-input-addon">
                            <i class="bi bi-envelope"></i>
                        </span>
                        <input type="email" class="form-control dc-input outfit-regular" id="email" name="email" placeholder="usuario@itsecure.sv" required autofocus>
                    </div>
                </div>

                <div class="d-grid mt-3">
                    <button type="submit" class="btn text-white py-3 dc-btn-gradient outfit-bold tracking-wide rounded-3">
                        <i class="bi bi-send-fill me-2 fs-5 align-middle"></i> Enviar Enlace
                    </button>
                </div>

                <div class="text-center mt-5">
                    <a href="{{ route('login') }}" class="text-cyan text-decoration-none outfit-medium dc-text-sm dc-hover-white transition-all">
                        <i class="bi bi-arrow-left me-1"></i> Volver al inicio de sesión
                    </a>
                </div>
            </form>

        </div>
    </div>
@endsection
