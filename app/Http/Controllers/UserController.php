<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActualizarPasswordRequest;
use App\Http\Requests\ActualizarUserRequest;
use App\Http\Requests\InsertarUsuarioRequest;
use App\Models\Rol;
use App\Models\User;
use App\Mail\BienvenidaMail;
use Illuminate\Support\Facades\Mail;

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
        $contrasenha = $datos['contrasenha'];
        $datos['contrasenha'] = bcrypt($datos['contrasenha']);
        $user = User::insertar($datos);
        Mail::to($user->email)->send(new BienvenidaMail($user->nombre, $user->email, $contrasenha));
        return redirect()->route('usuarios')->with('success', "Usuario {$user->nombre} ingresado correctamente.");
    }
    public function buscarUsuarioPorId(int $id)
    {
        return response()->json(User::buscarPorId($id));
    }
    public function actualizarUsuario(ActualizarUserRequest $r, User $usuario) //La variable debe ser igual al nombre del param de la ruta para el biding.
    {
        if($usuario->id === 1) {
            return redirect()->route('usuarios')->with('error', "No se puede actualizar al usuario admin.");
        }
        if(User::actualizar($usuario->id, $r->validated())) {
            return redirect()->route('usuarios')->with('success', "Usuario {$usuario->nombre} actualizado correctamente.");
        }
        return redirect()->route('usuarios')->with('error', "No se pudo actualizar al usuario.");
    }
    public function eliminarUsuario($id) {
        if($id === auth()->user()->id) return redirect()->route('usuarios')->with('error', "No te puedes eliminar a ti mismo.");

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
    public function perfil()
    {
        return view('usuario.perfil');
    }
    public function actualizarPassword(ActualizarPasswordRequest $r, User $usuario)
    {
        $datos = $r->validated();

        $resp = User::actualizarPassword($usuario->id, $datos);

        if($resp[0] === true) {
            return redirect()->route('usuario.perfil')->with('success', "Cambiaste tu contraseña correctamente.");
        } else {
            switch ($resp[1]) {
                case 0:
                    return redirect()->route('usuario.perfil')->with('error', "Hubo un problema en recuperar tus datos.");
                case 1:
                    return redirect()->route('usuario.perfil')->with('error', "Contraseña actual incorrecta.");
                case 2:
                    return redirect()->route('usuario.perfil')->with('error', "Contraseña nueva es la misma que la actual.");
                case 3:
                    return redirect()->route('usuario.perfil')->with('error', "Confirmación de contraseña no coincide con la contraseña nueva.");
                default:
                    return redirect()->route('usuario.perfil')->with('error', "Error inesperado.");
            }
        }
    }
}
