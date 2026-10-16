@extends('layouts.guest')

@section('titulo', 'Iniciar Sesión | IT-Secure')

@section('contenido')
    <div class="card shadow-lg w-100" style="max-width: 420px; background-color: var(--dc-bg-elevated); border: 1px solid var(--dc-border);">
        <div class="card-body p-4 p-sm-5">

            <div class="text-center mb-4">
                <div class="avatar-shield mb-3 mx-auto" style="width: 60px; height: 60px; font-size: 1.5rem;">
                    <i class="bi bi-shield-lock"></i>
                </div>
                <h4 class="text-white fw-bold">Acceso al Sistema</h4>
                <p class="text-muted" style="font-size: 0.9rem;">Ingresa tus credenciales para continuar</p>
            </div>

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label text-light" style="font-size: 0.9rem;">Correo Electrónico</label>
                    <div class="input-group">
                    <span class="input-group-text bg-dark border-secondary text-muted">
                        <i class="bi bi-envelope"></i>
                    </span>
                        <input type="email" class="form-control bg-dark border-secondary text-white" id="email" name="email" placeholder="user@itsecure.sv" required autofocus>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between">
                        <label for="password" class="form-label text-light" style="font-size: 0.9rem;">Contraseña</label>
                        <a href="#" class="text-cyan text-decoration-none" style="font-size: 0.85rem;">¿Olvidaste tu clave?</a>
                    </div>
                    <div class="input-group">
                    <span class="input-group-text bg-dark border-secondary text-muted">
                        <i class="bi bi-key"></i>
                    </span>
                        <input type="password" class="form-control bg-dark border-secondary text-white" id="password" name="contrasenha" placeholder="••••••••" required>
                    </div>
                </div>

                <div class="d-grid mt-2">
                    <button type="submit" class="btn text-white fw-bold py-2" style="background: linear-gradient(90deg, rgba(37, 99, 235, 0.8) 0%, rgba(56, 189, 248, 0.8) 100%); border: 1px solid var(--dc-cyan); box-shadow: 0 4px 15px var(--dc-glow);">
                        <i class="bi bi-box-arrow-in-right me-2"></i> Iniciar Sesión
                    </button>
                </div>
            </form>

        </div>
    </div>
@endsection
