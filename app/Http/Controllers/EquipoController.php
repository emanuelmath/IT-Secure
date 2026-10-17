<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\EquiposExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Equipo;

class EquipoController extends Controller
{
    public function index()
    {
        $equipos = Equipo::with(['centroDeComputo.sede'])->get();
        return view('equipo.equipos', compact('equipos'));
    }
    public function exportarExcel()
    {
        $fecha = date('Y-m-d_Hi');
        return Excel::download(new EquiposExport, "Inventario_Equipos_ITSecure_{$fecha}.xlsx");
    }

    public function exportarFichaPdf($id)
    {
        $equipo = Equipo::with([
            'centroDeComputo.sede.empresa',
            'registrador',
            'mantenimientos.usuario',
            'traslados.centroOrigen',
            'traslados.centroDestino',
            'traslados.autorizador'
        ])->findOrFail($id);

        $pdf = Pdf::loadView('reportes.equipo-ficha-pdf', compact('equipo'))
            ->setPaper('letter', 'portrait');

        return $pdf->stream("Ficha_Tecnica_{$equipo->codigo_inventario}.pdf");
    }
}
