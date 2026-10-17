<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Equipo;
use App\Models\HistorialTraslado;
use App\Models\Mantenimiento;

class HomeController extends Controller
{
    public function index()
    {
        return view('home.home');
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

        $traslados = HistorialTraslado::obtenerTrasladosUltimosSeisMeses();

        $trasladosLabels = $traslados->pluck('mes');
        $trasladosData = $traslados->pluck('total');

        return view('home.dashboard', compact(
            'estadoLabels', 'estadoData',
            'tipoLabels', 'tipoData',
            'mesesMant', 'mantPreventivos', 'mantCorrectivos',
            'equiposVencidos', 'totalVencidos',
            'trasladosLabels', 'trasladosData'
        ));
    }
}
