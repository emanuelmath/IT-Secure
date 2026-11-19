<?php

namespace App\Http\Controllers;

use App\Http\Requests\InsertarTrasladoRequest;
use App\Models\Bitacora;
use App\Models\CentroComputo;
use App\Models\Equipo;
use App\Models\HistorialTraslado;
use Barryvdh\DomPDF\Facade\Pdf;
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
            ->when(auth()->user()->tieneRol('Administrador') && session('empresa_activa_id'), function ($q) {
                $q->whereHas('sede', fn ($sq) => $sq->where('empresa_id', session('empresa_activa_id')));
            })
            ->when(!auth()->user()->tieneRol('Administrador'), function ($q) {
                $centrosUser = auth()->user()->centrosDeComputo()->with('sede')->get();
                $empresasIds = $centrosUser->pluck('sede.empresa_id')->filter()->unique();
                $q->whereHas('sede', fn ($sq) => $sq->whereIn('empresa_id', $empresasIds));
            })
            ->with('sede.empresa')
            ->get();

        return view('equipo.traslados', compact('traslados', 'equipos', 'centros'));
    }

    public function store(InsertarTrasladoRequest $request)
    {
        $datos = $request->validated();
        $equipo = Equipo::findOrFail($datos['equipo_id']);

        if ((int)$equipo->centros_de_computo_id !== (int)$datos['cc_origen_id']) {
            return back()->with('error', 'El centro de origen no coincide con la ubicación actual del equipo.');
        }

        DB::transaction(function () use ($datos, $equipo) {
            $traslado = HistorialTraslado::create([
                'equipo_id' => $equipo->id,
                'cc_origen_id' => $datos['cc_origen_id'],
                'cc_destino_id' => $datos['cc_destino_id'],
                'autorizado_por' => Auth::id(),
                'fecha_traslado' => $datos['fecha_traslado'],
                'motivo' => $datos['motivo'],
            ]);

            $equipo->update([
                'centros_de_computo_id' => $datos['cc_destino_id'],
            ]);

            Bitacora::registrar(
                'Traslado',
                'Traslados',
                "Se trasladó el equipo {$equipo->codigo_inventario} del centro ID {$datos['cc_origen_id']} al centro ID {$datos['cc_destino_id']}",
                $traslado->id
            );
        });

        return redirect()->route('traslados')->with('success', 'Traslado registrado y ubicación actualizada.');
    }

    public function actaPdf($id)
    {
        $traslado = HistorialTraslado::with([
            'equipo',
            'centroOrigen.sede.empresa',
            'centroDestino.sede.empresa',
            'autorizador',
        ])->findOrFail($id);

        Bitacora::registrar(
            'Exportar',
            'Traslados',
            "Se generó el acta oficial de traslado para el activo {$traslado->equipo->codigo_inventario} (ID: {$traslado->id})",
            $traslado->id
        );

        $pdf = Pdf::loadView('reportes.traslado-acta-pdf', compact('traslado'))
            ->setPaper('letter', 'portrait');

        return $pdf->stream("Acta_Traslado_{$traslado->id}_{$traslado->equipo->codigo_inventario}.pdf");
    }
}
