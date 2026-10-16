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
        // ==========================================
        // PILAR 1: GESTIÓN DE INVENTARIO
        // ==========================================

        // KPI 1: Estado (Donut)
        $kpiEstado = Equipo::filtrarEquipoPorEstado();
        $estadoLabels = $kpiEstado->pluck('estado');
        $estadoData = $kpiEstado->pluck('total');

        // KPI 2: Tipo (Barras Horizontales)
        $kpiTipo = Equipo::filtrarEquipoPorTipo();
        $tipoLabels = $kpiTipo->pluck('tipo');
        $tipoData = $kpiTipo->pluck('total');

        // ==========================================
        // PILAR 2: OPERATIVIDAD Y MANTENIMIENTO
        // ==========================================

        // KPI 3: Tendencia de Mantenimientos (Líneas múltiples)
        // Agrupamos por Mes (YYYY-MM) y Tipo.
        $mantenimientos = Mantenimiento::obtenerTendenciaPorMantenimiento();

        // Formatear data para Chart.js (Separar Preventivos de Correctivos)
        $mesesMant = $mantenimientos->pluck('mes')->unique()->values();
        $mantPreventivos = [];
        $mantCorrectivos = [];

        foreach ($mesesMant as $mes) {
            $prev = $mantenimientos->where('mes', $mes)->where('tipo', 'Preventivo')->first();
            $corr = $mantenimientos->where('mes', $mes)->where('tipo', 'Correctivo')->first();
            $mantPreventivos[] = $prev ? $prev->total : 0;
            $mantCorrectivos[] = $corr ? $corr->total : 0;
        }

        // KPI 4: Equipos Vencidos (Tarjetas y Tabla) - Usando el Scope
        $equiposVencidos = Equipo::vencidos()->with('centroDeComputo')->take(5)->get(); // Tomamos Top 5 para la vista rápida
        $totalVencidos = Equipo::vencidos()->count();

        // ==========================================
        // PILAR 3: MOVIMIENTOS Y LOGÍSTICA
        // ==========================================

        // KPI 5: Traslados (Barras Verticales Temporales)
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
