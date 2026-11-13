<?php

use App\Http\Controllers\EquipoController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\HomeController;
use \App\Http\Controllers\UserController;

$roles = ["Administrador", "Supervisor", "Técnico de Soporte"];

//Rutas generales.
Route::get('login', [LoginController::class, 'index'])->name('login');
Route::post('login', [LoginController::class, 'login'])->middleware('throttle:5,1');
Route::get('recuperar-contraseña', [LoginController::class, 'cambiarPassword'])->name('password.request');
Route::post('recuperar-contraseña', [LoginController::class, 'enviarCorreoPassword'])->name('password.email');
Route::get('restablecer-contraseña/{token}', [LoginController::class, 'vistaRestablecer'])->name('password.reset');
Route::post('restablecer-contraseña', [LoginController::class, 'actualizarPassword'])->name('password.update');


Route::middleware(['auth', 'prevent-back'])->group(function() {
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

//Rutas de Home.
    Route::get('/', [HomeController::class, 'index']);
    Route::get('home', [HomeController::class, 'index'])->name('home');
    Route::get('dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
    Route::get('mi-perfil', [UserController::class, 'perfil'])->name('usuario.perfil');
    Route::post('restablecer-contraseña-perfil/{usuario}', [UserController::class, 'actualizarPassword'])->name('password.update-profile');
});

// Solo Admin
Route::middleware(['auth', 'prevent-back', "rol:{$roles[0]}"])->group(function() {
//Rutas del Usuario.
    Route::get('usuarios', [UserController::class, 'index'])->name('usuarios');
    Route::post('usuarios', [UserController::class, 'insertarUsuario'])->name('usuarios.insertar');
//put
    Route::put('usuarios/{usuario}', [UserController::class, 'actualizarUsuario'])->name('usuarios.actualizar');
    Route::get('usuarios/{id}/buscar', [UserController::class, 'buscarUsuarioPorId'])->name('usuarios.buscar');
    Route::put('usuarios/activar/{usuario}', [UserController::class, 'activarUsuario'])->name('usuarios.activar');
//delete
    Route::delete('usuarios/{usuario}', [UserController::class, 'eliminarUsuario'])->name('usuarios.eliminar');

    Route::get('sedes', [\App\Http\Controllers\SedeController::class, 'index'])->name('sedes');
});

Route::middleware(['auth', 'prevent-back', "rol:{$roles[0]},{$roles[1]}"])->group(function() {
    Route::get('equipos/exportar/excel', [EquipoController::class, 'exportarExcel'])
        ->name('equipos.export.excel');

    Route::get('equipos/{id}/ficha-pdf', [EquipoController::class, 'exportarFichaPdf'])
        ->name('equipos.ficha.pdf');

    Route::get('equipos', [EquipoController::class, 'index'])->name('equipos');

    Route::post('equipos/{equipo}', [EquipoController::class, 'agregarEquipo'])->name("equipos.insertar");
});
