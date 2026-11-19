<?php

namespace App\Models;

use App\Traits\FiltrablePorContexto;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sede extends Model
{
    use SoftDeletes, FiltrablePorContexto;

    protected $table = 'sedes';

    protected $fillable = [
        'empresa_id',
        'nombre',
    ];

    protected $casts = [
        'empresa_id' => 'integer',
    ];

    private static function validaciones($id = null): array
    {
        return [
            'empresa_id' => ['required', 'integer', 'exists:empresas,id'],
            'nombre' => ['required', 'string', 'max:100'],
        ];
    }

    public static function validar(array $datos, $id = null): array
    {
        $mensajes = [
            'empresa_id.required' => 'Debe seleccionar una empresa.',
            'empresa_id.exists' => 'La empresa seleccionada no es válida.',
            'nombre.required' => 'El nombre de la sede es obligatorio.',
        ];

        return Validator::make($datos, static::validaciones($id), $mensajes)->validate();
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function centrosDeComputo(): HasMany
    {
        return $this->hasMany(CentroComputo::class, 'sede_id');
    }

    public static function obtenerSedes(): Collection
    {
        return self::all();
    }
}
