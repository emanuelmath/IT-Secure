<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rol extends Model
{
    use SoftDeletes;

    protected $table = 'roles';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    private static function validaciones($id = null): array
    {
        return [
            'nombre' => ['required', 'string', 'max:50', 'unique:roles,nombre,' . $id],
            'descripcion' => ['nullable', 'string', 'max:255'],
        ];
    }

    public static function validar(array $datos, $id = null): array
    {
        return Validator::make($datos, static::validaciones($id))->validate();
    }

    public function usuarios(): BelongsToMany
    {
        return $this->belongsToMany(Usuario::class, 'roles_usuarios', 'rol_id', 'usuario_id')->withTimestamps();
    }

    public static function obtenerTodos(): Collection
    {
        return self::all();
    }
}
