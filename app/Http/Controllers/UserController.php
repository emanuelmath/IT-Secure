<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActualizarPasswordRequest;
use App\Http\Requests\ActualizarUserRequest;
use App\Http\Requests\InsertarUsuarioRequest;
use App\Mail\BienvenidaMail;
use App\Models\Bitacora;
use App\Models\CentroComputo;
use App\Models\Rol;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function index()
    {
        $datos = [
            "usuarios" => User::with(['roles', 'centrosDeComputo.sede'])->get(),
            "roles" => Rol::obtenerTodos(),
            "centros" => CentroComputo::with('sede.empresa')->get(),
        ];
        return view('usuario.usuarios', data: $datos);
    }

    public function insertarUsuario(InsertarUsuarioRequest $request)
    {
        $datos = $request->validated();
        $contrasenha = $datos['contrasenha'];
        $datos['contrasenha'] = bcrypt($datos['contrasenha']);

        $user = User::insertar($datos);
        $user->centrosDeComputo()->sync($datos['centros'] ?? []);

        Mail::to($user->email)->send(new BienvenidaMail($user->nombre, $user->email, $contrasenha));
        Bitacora::registrar('Crear', 'Usuarios', "Se registró el usuario {$user->nombre} ({$user->email})", $user->id);

        return redirect()->route('usuarios')->with('success', "Usuario {$user->nombre} ingresado correctamente.");
    }

    public function buscarUsuarioPorId(int $id)
    {
        return response()->json(User::with(['roles', 'centrosDeComputo'])->find($id));
    }

    public function actualizarUsuario(ActualizarUserRequest $r, User $usuario)
    {
        if ($usuario->id === 1) {
            return redirect()->route('usuarios')->with('error', "No se puede actualizar al usuario admin.");
        }

        $datos = $r->validated();
        if (User::actualizar($usuario->id, $datos)) {
            $usuario->centrosDeComputo()->sync($datos['centros'] ?? []);
            Bitacora::registrar('Actualizar', 'Usuarios', "Se actualizó el usuario {$usuario->nombre}", $usuario->id);

            return redirect()->route('usuarios')->with('success', "Usuario {$usuario->nombre} actualizado correctamente.");
        }
        return redirect()->route('usuarios')->with('error', "No se pudo actualizar al usuario.");
    }

    public function eliminarUsuario($id)
    {
        if ((int)$id === auth()->user()->id) {
            return redirect()->route('usuarios')->with('error', "No te puedes eliminar a ti mismo.");
        }

        if (User::eliminar($id, auth()->id())) {
            Bitacora::registrar('Eliminar', 'Usuarios', "Se inhabilitó al usuario ID {$id}", $id);
            return redirect()->route('usuarios')->with('success', "Usuario eliminado correctamente.");
        }
        return redirect()->route('usuarios')->with('error', "No se pudo eliminar el usuario.");
    }

    public function activarUsuario($id)
    {
        if (User::activar($id)) {
            Bitacora::registrar('Activar', 'Usuarios', "Se reactivó al usuario ID {$id}", $id);
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

        if ($resp[0] === true) {
            Bitacora::registrar('Actualizar', 'Seguridad', "El usuario {$usuario->nombre} actualizó su contraseña", $usuario->id);
            return redirect()->route('usuario.perfil')->with('success', "Cambiaste tu contraseña correctamente.");
        } else {
            return match ($resp[1]) {
                0 => redirect()->route('usuario.perfil')->with('error', "Hubo un problema en recuperar tus datos."),
                1 => redirect()->route('usuario.perfil')->with('error', "Contraseña actual incorrecta."),
                2 => redirect()->route('usuario.perfil')->with('error', "Contraseña nueva es la misma que la actual."),
                3 => redirect()->route('usuario.perfil')->with('error', "Confirmación de contraseña no coincide con la contraseña nueva."),
                default => redirect()->route('usuario.perfil')->with('error', "Error inesperado."),
            };
        }
    }
}
