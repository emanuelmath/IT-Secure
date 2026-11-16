<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait FiltrablePorContexto
{
    protected static function bootFiltrablePorContexto(): void
    {
        static::addGlobalScope('contexto_seguridad', function (Builder $builder) {
            if (!Auth::check()) {
                return;
            }

            $user = Auth::user();
            $tabla = $builder->getModel()->getTable();

            if ($user->tieneRol('Administrador')) {
                $empresaId = session('empresa_activa_id');
                if (!$empresaId) {
                    return;
                }

                match ($tabla) {
                    'sedes' => $builder->where('empresa_id', $empresaId),
                    'centros_de_computo' => $builder->whereHas('sede', fn ($q) => $q->where('empresa_id', $empresaId)),
                    'equipos' => $builder->whereHas('centroDeComputo.sede', fn ($q) => $q->where('empresa_id', $empresaId)),
                    'mantenimientos' => $builder->whereHas('equipo.centroDeComputo.sede', fn ($q) => $q->where('empresa_id', $empresaId)),
                    'historial_traslados' => $builder->whereHas('equipo.centroDeComputo.sede', fn ($q) => $q->where('empresa_id', $empresaId)),
                    default => null,
                };

                return;
            }

            $centrosPermitidos = $user->centrosDeComputo()->pluck('centros_de_computo.id')->toArray();

            match ($tabla) {
                'centros_de_computo' => $builder->whereIn('id', $centrosPermitidos),
                'equipos' => $builder->whereIn('centros_de_computo_id', $centrosPermitidos),
                'mantenimientos' => $builder->whereHas('equipo', fn ($q) => $q->whereIn('centros_de_computo_id', $centrosPermitidos)),
                'historial_traslados' => $builder->where(function ($q) use ($centrosPermitidos) {
                    $q->whereIn('cc_origen_id', $centrosPermitidos)
                        ->orWhereIn('cc_destino_id', $centrosPermitidos);
                }),
                default => null,
            };
        });
    }
}
