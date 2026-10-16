<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Equipo extends Model
{

    // Primeras 3 -> Obligatorias
    use SoftDeletes;

    protected $table = 'equipos';

    protected $fillable = [
        'centros_de_computo_id',
        'codigo_inventario',
        'tipo',
        'marca',
        'modelo',
        'numero_serie',
        'estado',
        'dias_frecuencia_mantenimiento',
        'fecha_ultimo_mantenimiento',
        'registrado_por',
        'eliminado_por'
    ];

    protected $casts = [
        'centros_de_computo_id' => 'integer',
        'dias_frecuencia_mantenimiento' => 'integer',
        'fecha_ultimo_mantenimiento' => 'date',
        'registrado_por' => 'integer',
        'eliminado_por' => 'integer'
    ];

    private static function validaciones($id = null): array
    {
        return [
            'centros_de_computo_id' => ['required', 'integer', 'exists:centros_de_computo,id'],
            'codigo_inventario' => ['required', 'string', 'max:50', 'unique:equipos,codigo_inventario,' . $id],
            'tipo' => ['required', 'in:desktop,laptop,router,switch,servidor,impresora'],
            'marca' => ['nullable', 'string', 'max:50'],
            'modelo' => ['nullable', 'string', 'max:50'],
            'numero_serie' => ['nullable', 'string', 'max:100'],
            'estado' => ['required', 'in:operativo,mantenimiento,averiado,baja_definitiva'],
            'dias_frecuencia_mantenimiento' => ['required', 'integer', 'min:1'],
            'fecha_ultimo_mantenimiento' => ['nullable', 'date'],
            'registrado_por' => ['nullable', 'integer', 'exists:usuarios,id'],
            'eliminado_por' => ['nullable', 'integer', 'exists:usuarios,id']
        ];
    }

    public static function validar(array $datos, $id = null): array
    {
        return Validator::make($datos, static::validaciones($id))->validate();
    }
    public function centroDeComputo(): BelongsTo
    {
        return $this->belongsTo(CentroComputo::class, 'centros_de_computo_id');
    }

    public function registrador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registrado_por');
    }

    public function eliminador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'eliminado_por');
    }

    public function mantenimientos(): HasMany
    {
        return $this->hasMany(Mantenimiento::class, 'equipo_id');
    }

    public function traslados(): HasMany
    {
        return $this->hasMany(HistorialTraslado::class, 'equipo_id');
    }

    public static function filtrarEquipoPorEstado(): Collection
    {
        return Equipo::select('estado', DB::raw('count(*) as total'))
            ->groupBy('estado')
            ->get();
    }

    public static function filtrarEquipoPorTipo(): Collection {
        return Equipo::select('tipo', DB::raw('count(*) as total'))->groupBy('tipo')->get();
    }

    public function scopeVencidos($query)
    {
        return $query->whereRaw('DATE_ADD(fecha_ultimo_mantenimiento, INTERVAL dias_frecuencia_mantenimiento DAY) < CURDATE()');
    }
}
