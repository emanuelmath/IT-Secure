<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empresa extends Model
{
    use SoftDeletes;
    use HasFactory;
    //public $timestamps = false; si no tiene timestapms
    protected $table = 'empresas';

    protected $fillable = [
        'nombre',
        'nit',
    ];

    private static function validaciones($id = null): array
    {
        return [
            'nombre' => ['required', 'string', 'max:150'],
            'nit' => ['required', 'string', 'max:20', 'unique:empresas,nit,' . $id],
        ];
    }

    public static function validar(array $datos, $id = null): array
    {
        return Validator::make($datos, static::validaciones($id))->validate();
    }

    public function sedes(): HasMany
    {
        return $this->hasMany(Sede::class, 'empresa_id');
    }

    public static function contarTodos(): int {
        return self::count();
    }

    public static function mostrarTodos(): Collection {
        return self::all();
    }

    public static function buscarPorId($id): Empresa
    {
        return self::find($id);
    }

    public static function buscarPorNombre($nombre): Empresa
    {
        return self::where('nombre', 'like', "%{$nombre}%")->get();


    }
}

