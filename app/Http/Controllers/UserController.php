<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActualizarUserRequest;
use App\Http\Requests\InsertarUsuarioRequest;
use App\Models\Rol;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        $datos = [
            "usuarios" => User::mostrarTodos(),
            "roles" => Rol::obtenerTodos()
        ];
        return view('usuario.usuarios', data: $datos);
    }

    public function insertarUsuario(InsertarUsuarioRequest $request)
    {
        $datos = $request->validated();
        $datos['contrasenha'] = bcrypt($datos['contrasenha']);
        $user = User::insertar($datos);
        return redirect()->route('usuarios')->with('success', "Usuario {$user->nombre} ingresado correctamente");
    }
    public function buscarUsuarioPorId(int $id)
    {
        return response()->json(User::buscarPorId($id));
    }
    public function actualizarUsuario(ActualizarUserRequest $r, User $usuario) //La variable debe ser igual al nombre del param de la ruta para el biding.
    {
        if(User::actualizar($usuario->id, $r->validated())) {
            return redirect()->route('usuarios')->with('success', "Usuario {$usuario->nombre} actualizado correctamente.");
        }
        return redirect()->route('usuarios')->with('error', "No se pudo actualizar al usuario.");
    }
    public function eliminarUsuario($id) {
        if(User::eliminar($id)) {
            return redirect()->route('usuarios')->with('success', "Usuario eliminado correctamente.");
        }
        return redirect()->route('usuarios')->with('error', "No se pudo eliminar el usuario.");
    }
    public function activarUsuario($id)
    {
        if(User::activar($id)) {
            return redirect()->route('usuarios')->with('success', "Usuario activado correctamente.");
        }
        return redirect()->route('usuarios')->with('error', "No se pudo activar al usuario.");
    }
}
