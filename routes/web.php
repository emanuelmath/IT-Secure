<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\HomeController;
use \App\Http\Controllers\UserController;

//Rutas generales.
Route::get('login', [LoginController::class, 'index'])->name('login');
Route::post('login', [LoginController::class, 'login'])->middleware('throttle:5,1');


Route::middleware('auth')->group(function() {
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

//Rutas de Home.
    Route::get('/', [HomeController::class, 'index']);
    Route::get('home', [HomeController::class, 'index'])->name('home');
    Route::get('dashboard', [HomeController::class, 'dashboard'])->name('dashboard');

});

Route::middleware(['auth', 'rol'])->group(function() {
//Rutas del Usuario.
    Route::get('usuarios', [UserController::class, 'index'])->name('usuarios');
    Route::post('usuarios', [UserController::class, 'insertarUsuario'])->name('usuarios.insertar');
//put
    Route::put('usuarios/{usuario}', [UserController::class, 'actualizarUsuario'])->name('usuarios.actualizar');
    Route::get('usuarios/{id}/buscar', [UserController::class, 'buscarUsuarioPorId'])->name('usuarios.buscar');
    Route::put('usuarios/activar/{usuario}', [UserController::class, 'activarUsuario'])->name('usuarios.activar');
//delete
    Route::delete('usuarios/{usuario}', [UserController::class, 'eliminarUsuario'])->name('usuarios.eliminar');
});
