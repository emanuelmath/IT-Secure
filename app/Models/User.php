<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use function Laravel\Prompts\select;
use function Symfony\Component\String\s;

class User extends Authenticatable
{
    use SoftDeletes;

    protected $table = 'usuarios';

    protected $fillable = [
        'nombre',
        'email',
        'activo',
        'email_verificado_hace',
        'contrasenha'
    ];

    protected $casts = [
        'contrasenha' => 'hashed',
    ];

    protected $hidden = [
        'email_verificado_hace',
        'contrasenha'
    ];

    public static function validaciones($id = null): array
    {
        return [
            'nombre' => ['required', 'string', 'max:60'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:usuarios,email,' . $id],
            'email_verificado_hace' => ['nullable', 'date'],
            'contrasenha' => $id ? ['nullable', 'string', 'min:8'] : ['required', 'string', 'min:8'],
            'roles' => ['required', 'array'],
            'roles.*' => ['integer']
        ];
    }

    public static function loginRules(): array
    {
        return [
            'email' => [
                'required',
                'string',
                'email'
            ],
            'contrasenha' => [
                'required',
                'string'
            ]
        ];
    }

    public static function recuperarRules(): array
    {
        return [
            'email' => [
                'required',
                'string',
                'email'
            ]
        ];
    }

    public static function actualizarPasswordRules(): array
    {
        return [
            'contrasenha' => [
                'required',
                'string'
            ],
            'contrasenhaNueva' => [
                'required',
                'string',
                'min:8'
            ],
            'contrasenhaConfirmar' => [
                'required',
                'string',
                'min:8',
                'same:contrasenhaNueva'
            ]
        ];
    }

    public function getAuthPassword(): string
    {
        return $this->contrasenha;
    }

    public static function validar(array $datos, $id = null): array
    {
        return Validator::make($datos, static::validaciones($id))->validate();
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Rol::class, 'roles_usuarios', 'usuario_id', 'rol_id')->withTimestamps();
    }

    public function centrosDeComputo(): BelongsToMany
    {
        return $this->belongsToMany(CentroComputo::class, 'centros_de_computo_usuarios', 'usuario_id', 'centros_de_computo_id')->withTimestamps();
    }

    public static function mostrarTodos() : Collection
    {
        return self::with('roles')->get();
    }

    public static function insertar($datos): User
    {
        return DB::transaction(function () use ($datos) {

            $roles = $datos['roles'] ?? [];
            unset($datos['roles']);

            $user = self::create($datos);
            if (!empty($roles)) {
                $user->roles()->sync($roles);
            }

            return $user;
        });
    }

    public static function buscarPorId($id): ?User {
        return User::with('roles')->find($id);
    }

    public static function actualizar($id, $datos): bool
    {
        if ($id == 1) return false;

        $usuario = self::find($id);
        if (!$usuario) return false;

        if (empty($datos['contrasenha'])) {
            unset($datos['contrasenha']);
        }

        return DB::transaction(function () use ($usuario, $datos) {
            $roles = $datos['roles'] ?? [];
            unset($datos['roles']);

            $usuario->update($datos);
            $usuario->roles()->sync($roles);

            return true;
        });
    }

    public static function eliminar($id, $miID): bool
    {
        if($id == 1) return false;

        if($id === $miID) return false;

        $usuario = self::find($id);
        if (!$usuario) return false;

        $usuario->activo = false;
        return $usuario->save();
        //return User::find($id)->update(['activo' => false]);
        //return User::find($id)->delete();
    }

    public static function activar($id): bool
    {
        $usuario = self::find($id);
        if (!$usuario) return false;

        $usuario->activo = true;
        return $usuario->save();
    }

    public function tieneAlgunRol(array $rolesNombres):
    bool
    {
        return $this->roles->pluck('nombre')->intersect($rolesNombres)->isNotEmpty();
    }
    public function obtenerRolesNombres(): array
    {
        return $this->roles->pluck('nombre')->toArray();
    }

    public function tieneRol(string|array $nombreRol): bool
    {
        if (is_array($nombreRol)) {
            return $this->roles->whereIn('nombre', $nombreRol)->isNotEmpty();
        }

        return $this->roles->contains('nombre', $nombreRol);
    }

    public static function actualizarPassword($id, $datos): array
    {
        $usuario = self::buscarPorId($id);

        if($usuario === null) return [false, 0];
        if(!Hash::check($datos['contrasenha'], $usuario->contrasenha)) return [false, 1];
        if(Hash::check($datos['contrasenhaNueva'], $usuario->contrasenha)) return [false, 2];
        if($datos['contrasenhaNueva'] !== $datos['contrasenhaConfirmar']) return [false, 3];

        $usuario->contrasenha = bcrypt($datos['contrasenhaNueva']);
        return [$usuario->save(), -1];
    }

    public static function estaActivo($correo): bool
    {
        $u = self::where('email', $correo)->first();

        if($u === null) return false;

        return $u->activo;
    }
}
