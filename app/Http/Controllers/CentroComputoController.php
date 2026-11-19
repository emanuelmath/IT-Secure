<?php

namespace App\Http\Controllers;

use App\Http\Requests\CentroComputoRequest;
use App\Models\Bitacora;
use App\Models\CentroComputo;
use App\Models\Sede;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class CentroComputoController extends Controller
{
    public function index()
    {
        $centros = CentroComputo::with(['sede.empresa', 'equipos'])
            ->withCount('equipos')
            ->get();

        $sedes = Sede::with('empresa')->get();

        return view('infraestructura.centros', compact('centros', 'sedes'));
    }

    public function reportePdf($id)
    {
        $centro = CentroComputo::with(['sede.empresa', 'equipos'])->findOrFail($id);

        $equipos = $centro->equipos;
        $totalEquipos = $equipos->count();
        $operativos = $equipos->where('estado', 'operativo')->count();
        $enMantenimiento = $equipos->where('estado', 'mantenimiento')->count();
        $averiados = $equipos->where('estado', 'averiado')->count();
        $bajas = $equipos->where('estado', 'baja_definitiva')->count();
        $desgloseTipos = $equipos->groupBy('tipo')->map->count();

        Bitacora::registrar(
            'Exportar',
            'Centros de Cómputo',
            "Se generó el reporte ejecutivo de estado para el centro {$centro->nombre} ({$centro->codigo})",
            $centro->id
        );

        $pdf = Pdf::loadView('reportes.centro-resumen-pdf', compact(
            'centro',
            'equipos',
            'totalEquipos',
            'operativos',
            'enMantenimiento',
            'averiados',
            'bajas',
            'desgloseTipos'
        ))->setPaper('letter', 'portrait');

        return $pdf->stream("Reporte_Estado_{$centro->codigo}.pdf");
    }

    public function store(CentroComputoRequest $request)
    {
        $datos = $request->validated();
        $centro = CentroComputo::create($datos);

        Bitacora::registrar(
            'Crear',
            'Centros de Cómputo',
            "Se registró el centro {$centro->nombre} (Código: {$centro->codigo})",
            $centro->id
        );

        return redirect()->route('centros')->with('success', 'Centro de cómputo registrado correctamente.');
    }

    public function update(CentroComputoRequest $request, CentroComputo $centro)
    {
        $datos = $request->validated();
        $centro->update($datos);

        Bitacora::registrar(
            'Actualizar',
            'Centros de Cómputo',
            "Se actualizó el centro {$centro->nombre} (Código: {$centro->codigo})",
            $centro->id
        );

        return redirect()->route('centros')->with('success', 'Centro de cómputo actualizado correctamente.');
    }

    public function destroy(CentroComputo $centro)
    {
        $nombre = $centro->nombre;
        $codigo = $centro->codigo;
        $id = $centro->id;

        $centro->delete();

        Bitacora::registrar(
            'Eliminar',
            'Centros de Cómputo',
            "Se dio de baja el centro {$nombre} (Código: {$codigo})",
            $id
        );

        return redirect()->route('centros')->with('success', 'Centro de cómputo eliminado correctamente.');
    }
}
