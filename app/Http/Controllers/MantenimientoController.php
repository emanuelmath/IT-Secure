<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use App\Models\Equipo;
use App\Models\Mantenimiento;
use Illuminate\Http\Request;
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

    public function store(Request $request)
    {
        $request->validate([
            'equipo_id' => ['required', 'integer', 'exists:equipos,id'],
            'tipo' => ['required', 'in:preventivo,correctivo'],
            'fecha_mantenimiento' => ['required', 'date'],
            'descripcion_trabajo' => ['required', 'string'],
            'piezas_reemplazadas' => ['nullable', 'string'],
            'estado_equipo' => ['required', 'in:operativo,mantenimiento,averiado'],
        ]);

        DB::transaction(function () use ($request) {
            $equipo = Equipo::findOrFail($request->equipo_id);

            $mantenimiento = Mantenimiento::create([
                'equipo_id' => $equipo->id,
                'usuario_id' => Auth::id(),
                'tipo' => $request->tipo,
                'fecha_mantenimiento' => $request->fecha_mantenimiento,
                'descripcion_trabajo' => $request->descripcion_trabajo,
                'piezas_reemplazadas' => $request->piezas_reemplazadas,
            ]);

            $equipo->update([
                'fecha_ultimo_mantenimiento' => $request->fecha_mantenimiento,
                'estado' => $request->estado_equipo,
            ]);

            Bitacora::registrar(
                'Crear',
                'Mantenimientos',
                "Se registró mantenimiento {$request->tipo} al equipo {$equipo->codigo_inventario} (Nuevo estado: {$request->estado_equipo})",
                $mantenimiento->id
            );
        });

        return redirect()->route('mantenimientos')->with('success', 'Mantenimiento registrado correctamente.');
    }
}
