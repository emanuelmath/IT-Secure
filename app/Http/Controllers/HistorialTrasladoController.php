<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use App\Models\CentroComputo;
use App\Models\Equipo;
use App\Models\HistorialTraslado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HistorialTrasladoController extends Controller
{
    public function index()
    {
        $traslados = HistorialTraslado::with([
            'equipo',
            'centroOrigen.sede',
            'centroDestino.sede',
            'autorizador',
        ])->orderByDesc('fecha_traslado')->get();

        $equipos = Equipo::with('centroDeComputo.sede')
            ->where('estado', '!=', 'baja_definitiva')
            ->get();

        $centros = CentroComputo::withoutGlobalScope('contexto_seguridad')
            ->with('sede.empresa')
            ->get();

        return view('equipo.traslados', compact('traslados', 'equipos', 'centros'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'equipo_id' => ['required', 'integer', 'exists:equipos,id'],
            'cc_origen_id' => ['required', 'integer', 'exists:centros_de_computo,id'],
            'cc_destino_id' => ['required', 'integer', 'exists:centros_de_computo,id', 'different:cc_origen_id'],
            'fecha_traslado' => ['required', 'date'],
            'motivo' => ['required', 'string'],
        ]);

        DB::transaction(function () use ($request) {
            $equipo = Equipo::findOrFail($request->equipo_id);

            $traslado = HistorialTraslado::create([
                'equipo_id' => $equipo->id,
                'cc_origen_id' => $request->cc_origen_id,
                'cc_destino_id' => $request->cc_destino_id,
                'autorizado_por' => Auth::id(),
                'fecha_traslado' => $request->fecha_traslado,
                'motivo' => $request->motivo,
            ]);

            $equipo->update([
                'centros_de_computo_id' => $request->cc_destino_id,
            ]);

            Bitacora::registrar(
                'Traslado',
                'Traslados',
                "Se trasladó el equipo {$equipo->codigo_inventario} del centro ID {$request->cc_origen_id} al centro ID {$request->cc_destino_id}",
                $traslado->id
            );
        });

        return redirect()->route('traslados')->with('success', 'Traslado registrado y ubicación actualizada.');
    }
}
