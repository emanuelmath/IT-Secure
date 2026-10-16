<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class HistorialTraslado extends Model
{
    protected $table = 'historial_traslados';

    protected $fillable = [
        'equipo_id',
        'cc_origen_id',
        'cc_destino_id',
        'autorizado_por',
        'fecha_traslado',
        'motivo',
    ];

    protected $casts = [
        'equipo_id' => 'integer',
        'cc_origen_id' => 'integer',
        'cc_destino_id' => 'integer',
        'autorizado_por' => 'integer',
        'fecha_traslado' => 'date',
    ];

    private static function validaciones($id = null): array
    {
        return [
            'equipo_id' => ['required', 'integer', 'exists:equipos,id'],
            'cc_origen_id' => ['required', 'integer', 'exists:centros_de_computo,id'],
            'cc_destino_id' => ['required', 'integer', 'exists:centros_de_computo,id'],
            'autorizado_por' => ['required', 'integer', 'exists:usuarios,id'],
            'fecha_traslado' => ['required', 'date'],
            'motivo' => ['required', 'string'],
        ];
    }

    public static function validar(array $datos, $id = null): array
    {
        return Validator::make($datos, static::validaciones($id))->validate();
    }

    public function equipo(): BelongsTo
    {
        return $this->belongsTo(Equipo::class, 'equipo_id');
    }

    public function centroOrigen(): BelongsTo
    {
        return $this->belongsTo(CentroComputo::class, 'cc_origen_id');
    }

    public function centroDestino(): BelongsTo
    {
        return $this->belongsTo(CentroComputo::class, 'cc_destino_id');
    }

    public function autorizador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'autorizado_por');
    }

    public static function obtenerTrasladosUltimosSeisMeses(): Collection
    {
        return self::select(
            DB::raw("DATE_FORMAT(fecha_traslado, '%Y-%m') as mes"),
            DB::raw('count(*) as total')
        )
            ->groupBy('mes')
            ->orderBy('mes')
            ->take(6)
            ->get();
    }
}
