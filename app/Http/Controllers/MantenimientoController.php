<?php

namespace App\Http\Controllers;

use App\Http\Requests\InsertarMantenimientoRequest;
use App\Models\Bitacora;
use App\Models\Equipo;
use App\Models\Mantenimiento;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MantenimientoController extends Controller
{
    public function index()
    {
        $mantenimientos = Mantenimiento::with(['equipo.centroDeComputo', 'usuario'])
            ->orderByDesc('fecha_mantenimiento')
            ->get();

        $equipos = Equipo::with('centroDeComputo')
            ->where('estado', '!=', 'baja_definitiva')
            ->get();

        return view('mantenimiento.mantenimientos', compact('mantenimientos', 'equipos'));
    }

    public function store(InsertarMantenimientoRequest $request)
    {
        $datos = $request->validated();

        DB::transaction(function () use ($datos) {
            $equipo = Equipo::findOrFail($datos['equipo_id']);

            $mantenimiento = Mantenimiento::create([
                'equipo_id' => $equipo->id,
                'usuario_id' => Auth::id(),
                'tipo' => $datos['tipo'],
                'fecha_mantenimiento' => $datos['fecha_mantenimiento'],
                'descripcion_trabajo' => $datos['descripcion_trabajo'],
                'piezas_reemplazadas' => $datos['piezas_reemplazadas'] ?? null,
            ]);

            $equipo->update([
                'fecha_ultimo_mantenimiento' => $datos['fecha_mantenimiento'],
                'estado' => $datos['estado_equipo'],
            ]);

            Bitacora::registrar(
                'Crear',
                'Mantenimientos',
                "Se registró mantenimiento {$datos['tipo']} al equipo {$equipo->codigo_inventario} (Nuevo estado: {$datos['estado_equipo']})",
                $mantenimiento->id
            );
        });

        return redirect()->route('mantenimientos')->with('success', 'Mantenimiento registrado correctamente.');
    }
}
