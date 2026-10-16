<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Collection;

class Mantenimiento extends Model
{
    use SoftDeletes;

    protected $table = 'mantenimientos';

    protected $fillable = [
        'equipo_id',
        'usuario_id',
        'tipo',
        'descripcion_trabajo',
        'piezas_reemplazadas',
        'fecha_mantenimiento',
    ];

    protected $casts = [
        'equipo_id' => 'integer',
        'usuario_id' => 'integer',
        'fecha_mantenimiento' => 'date',
    ];

    private static function validaciones($id = null): array
    {
        return [
            'equipo_id' => ['required', 'integer', 'exists:equipos,id'],
            'usuario_id' => ['required', 'integer', 'exists:usuarios,id'],
            'tipo' => ['required', 'in:preventivo,correctivo'],
            'descripcion_trabajo' => ['required', 'string'],
            'piezas_reemplazadas' => ['nullable', 'string'],
            'fecha_mantenimiento' => ['required', 'date'],
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

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public static function obtenerTendenciaPorMantenimiento(): Collection {
        return self::select(
            DB::raw("DATE_FORMAT(fecha_mantenimiento, '%Y-%m') as mes"),
            'tipo',
            DB::raw('count(*) as total')
        )
            ->groupBy('mes', 'tipo')
            ->orderBy('mes')
            ->get();
    }
}
