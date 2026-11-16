<?php

use App\Http\Controllers\BitacoraController;
use App\Http\Controllers\CentroComputoController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\HistorialTrasladoController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MantenimientoController;
use App\Http\Controllers\SedeController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

$roles = ["Administrador", "Supervisor", "Técnico de Soporte"];

// Rutas públicas y/o de Autenticación.
Route::get('login', [LoginController::class, 'index'])->name('login');
Route::post('login', [LoginController::class, 'login'])->middleware('throttle:5,1');
Route::get('recuperar-contraseña', [LoginController::class, 'cambiarPassword'])->name('password.request');
Route::post('recuperar-contraseña', [LoginController::class, 'enviarCorreoPassword'])->name('password.email');
Route::get('restablecer-contraseña/{token}', [LoginController::class, 'vistaRestablecer'])->name('password.reset');
Route::post('restablecer-contraseña', [LoginController::class, 'actualizarPassword'])->name('password.update');

// Rutas autenticadas generales (Todos los roles).
Route::middleware(['auth', 'prevent-back'])->group(function() {
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    // Home & Dashboard.
    Route::get('/', [HomeController::class, 'index']);
    Route::get('home', [HomeController::class, 'index'])->name('home');
    Route::get('dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
    Route::get('home/acerca', [HomeController::class, 'acerca'])->name('acerca');

    // Perfil y cambio de contraseña en sesión.
    Route::get('mi-perfil', [UserController::class, 'perfil'])->name('usuario.perfil');
    Route::post('restablecer-contraseña-perfil/{usuario}', [UserController::class, 'actualizarPassword'])->name('password.update-profile');

    // Mantenimientos (Accesible para Admin, Supervisor y Técnico según centros asignados).
    Route::get('mantenimientos', [MantenimientoController::class, 'index'])->name('mantenimientos');
    Route::post('mantenimientos', [MantenimientoController::class, 'store'])->name('mantenimientos.insertar');
});

// Rutas exclusivas de Administrador.
Route::middleware(['auth', 'prevent-back', "rol:{$roles[0]}"])->group(function() {
    // Selector Global de Contexto de Empresa.
    Route::post('contexto/empresa', function (Request $request) {
        session(['empresa_activa_id' => $request->empresa_id ?: null]);
        return back();
    })->name('contexto.empresa');

    // Gestión de Usuarios.
    Route::get('usuarios', [UserController::class, 'index'])->name('usuarios');
    Route::post('usuarios', [UserController::class, 'insertarUsuario'])->name('usuarios.insertar');
    Route::put('usuarios/{usuario}', [UserController::class, 'actualizarUsuario'])->name('usuarios.actualizar');
    Route::get('usuarios/{id}/buscar', [UserController::class, 'buscarUsuarioPorId'])->name('usuarios.buscar');
    Route::put('usuarios/activar/{usuario}', [UserController::class, 'activarUsuario'])->name('usuarios.activar');
    Route::delete('usuarios/{usuario}', [UserController::class, 'eliminarUsuario'])->name('usuarios.eliminar');

    // Gestión de Empresas.
    Route::get('empresas', [EmpresaController::class, 'index'])->name('empresas');
    Route::post('empresas', [EmpresaController::class, 'store'])->name('empresas.insertar');
    Route::put('empresas/{empresa}', [EmpresaController::class, 'update'])->name('empresas.actualizar');
    Route::delete('empresas/{empresa}', [EmpresaController::class, 'destroy'])->name('empresas.eliminar');

    // Gestión de Sedes.
    Route::get('sedes', [SedeController::class, 'index'])->name('sedes');
    Route::post('sedes', [SedeController::class, 'store'])->name('sedes.insertar');
    Route::put('sedes/{sede}', [SedeController::class, 'update'])->name('sedes.actualizar');
    Route::delete('sedes/{sede}', [SedeController::class, 'destroy'])->name('sedes.eliminar');

    // Gestión de Centros de Cómputo.
    Route::get('centros-computo', [CentroComputoController::class, 'index'])->name('centros');
    Route::post('centros-computo', [CentroComputoController::class, 'store'])->name('centros.insertar');
    Route::put('centros-computo/{centro}', [CentroComputoController::class, 'update'])->name('centros.actualizar');
    Route::delete('centros-computo/{centro}', [CentroComputoController::class, 'destroy'])->name('centros.eliminar');

    // Bitácoras de Auditoría.
    Route::get('bitacoras', [BitacoraController::class, 'index'])->name('bitacoras');
});

// Rutas de Administrador y Supervisor.
Route::middleware(['auth', 'prevent-back', "rol:{$roles[0]},{$roles[1]}"])->group(function() {
    // Reportes y Exportaciones de Equipos
    Route::get('equipos/exportar/excel', [EquipoController::class, 'exportarExcel'])->name('equipos.export.excel');
    Route::get('equipos/{id}/ficha-pdf', [EquipoController::class, 'exportarFichaPdf'])->name('equipos.ficha.pdf');

    // CRUD de Equipos.
    Route::get('equipos', [EquipoController::class, 'index'])->name('equipos');
    Route::post('equipos', [EquipoController::class, 'agregarEquipo'])->name('equipos.insertar');
    Route::put('equipos/{equipo}', [EquipoController::class, 'actualizarEquipo'])->name('equipos.actualizar');
    Route::delete('equipos/{equipo}', [EquipoController::class, 'eliminarEquipo'])->name('equipos.eliminar');

    // Historial de Traslados.
    Route::get('traslados', [HistorialTrasladoController::class, 'index'])->name('traslados');
    Route::post('traslados', [HistorialTrasladoController::class, 'store'])->name('traslados.insertar');
});
