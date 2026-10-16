<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\SoftDeletes;

class CentroComputo extends Model
{
    use SoftDeletes;

    protected $table = 'centros_de_computo';

    protected $fillable = [
        'sede_id',
        'codigo',
        'nombre',
    ];

    protected $casts = [
        'sede_id' => 'integer',
    ];

    private static function validaciones($id = null): array
    {
        return [
            'sede_id' => ['required', 'integer', 'exists:sedes,id'],
            'codigo' => ['required', 'string', 'max:50'],
            'nombre' => ['required', 'string', 'max:100'],
        ];
    }

    public static function validar(array $datos, $id = null): array
    {
        return Validator::make($datos, static::validaciones($id))->validate();
    }

    public function sede(): BelongsTo
    {
        return $this->belongsTo(Sede::class, 'sede_id');
    }

    public function equipos(): HasMany
    {
        return $this->hasMany(Equipo::class, 'centros_de_computo_id');
    }

    public function usuarios(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'centros_de_computo_usuarios', 'centros_de_computo_id', 'usuario_id')->withTimestamps();
    }
}
