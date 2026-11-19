<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use App\Models\Equipo;
use App\Models\HistorialTraslado;
use App\Models\Mantenimiento;

class HomeController extends Controller
{
    public function index()
    {
        $totalVencidos = Equipo::vencidos()->count();
        $ultimasBitacoras = Bitacora::with('usuario')
            ->orderByDesc('created_at')
            ->take(3)
            ->get();

        return view('home.home', compact('totalVencidos', 'ultimasBitacoras'));
    }

    public function acerca()
    {
        return view('home.acerca');
    }

    public function dashboard()
    {
        $kpiEstado = Equipo::filtrarEquipoPorEstado();
        $estadoLabels = $kpiEstado->pluck('estado');
        $estadoData = $kpiEstado->pluck('total');

        $kpiTipo = Equipo::filtrarEquipoPorTipo();
        $tipoLabels = $kpiTipo->pluck('tipo');
        $tipoData = $kpiTipo->pluck('total');

        $mantenimientos = Mantenimiento::obtenerTendenciaPorMantenimiento();

        $mesesMant = collect();
        for ($i = 5; $i >= 0; $i--) {
            $mesesMant->push(now()->subMonths($i)->format('Y-m'));
        }

        $mantPreventivos = [];
        $mantCorrectivos = [];

        foreach ($mesesMant as $mes) {
            $prev = $mantenimientos->where('mes', $mes)->where('tipo', 'preventivo')->first();
            $corr = $mantenimientos->where('mes', $mes)->where('tipo', 'correctivo')->first();

            $mantPreventivos[] = $prev ? $prev->total : 0;
            $mantCorrectivos[] = $corr ? $corr->total : 0;
        }

        $equiposVencidos = Equipo::vencidos()->with('centroDeComputo')->take(5)->get();
        $totalVencidos = Equipo::vencidos()->count();
        $equiposProximos = Equipo::proximosVencer(7)->with('centroDeComputo')->take(5)->get();
        $totalProximos = Equipo::proximosVencer(7)->count();

        // Toma de decisiones: Equipos con fallas correctivas recurrentes (Candidatos a baja definitiva)
        $equiposFallasRecurrentes = Equipo::with(['centroDeComputo.sede'])
            ->withCount(['mantenimientos as correctivos_count' => function ($q) {
                $q->where('tipo', 'correctivo');
            }])
            ->where('estado', '!=', 'baja_definitiva')
            ->whereHas('mantenimientos', function ($q) {
                $q->where('tipo', 'correctivo');
            })
            ->orderByDesc('correctivos_count')
            ->take(6)
            ->get();

        $decisionLabels = $equiposFallasRecurrentes->pluck('codigo_inventario');
        $decisionData = $equiposFallasRecurrentes->pluck('correctivos_count');

        $traslados = HistorialTraslado::obtenerTrasladosUltimosSeisMeses();

        $trasladosLabels = $traslados->pluck('mes');
        $trasladosData = $traslados->pluck('total');

        return view('home.dashboard', compact(
            'estadoLabels', 'estadoData',
            'tipoLabels', 'tipoData',
            'mesesMant', 'mantPreventivos', 'mantCorrectivos',
            'equiposVencidos', 'totalVencidos',
            'equiposProximos', 'totalProximos',
            'equiposFallasRecurrentes', 'decisionLabels', 'decisionData',
            'trasladosLabels', 'trasladosData'
        ));
    }
}
