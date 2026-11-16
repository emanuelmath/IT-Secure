<?php

namespace App\Http\Controllers;

use App\Exports\EquiposExport;
use App\Http\Requests\InsertarEquipoRequest;
use App\Models\Bitacora;
use App\Models\CentroComputo;
use App\Models\Equipo;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class EquipoController extends Controller
{
    public function index()
    {
        $equipos = Equipo::with(['centroDeComputo.sede'])->get();
        $centros = CentroComputo::with('sede.empresa')->get();

        return view('equipo.equipos', compact('equipos', 'centros'));
    }

    public function exportarExcel()
    {
        $fecha = date('Y-m-d_Hi');
        Bitacora::registrar('Exportar', 'Equipos', 'Se exportó el inventario general de equipos a Excel');

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
            'traslados.autorizador',
        ])->findOrFail($id);

        Bitacora::registrar('Exportar', 'Equipos', "Se generó la ficha técnica PDF del equipo {$equipo->codigo_inventario}", $equipo->id);

        $pdf = Pdf::loadView('reportes.equipo-ficha-pdf', compact('equipo'))
            ->setPaper('letter', 'portrait');

        return $pdf->stream("Ficha_Tecnica_{$equipo->codigo_inventario}.pdf");
    }

    public function agregarEquipo(InsertarEquipoRequest $request)
    {
        $datos = $request->validated();
        $datos['registrado_por'] = Auth::id();

        $equipo = Equipo::insertar($datos);

        Bitacora::registrar(
            'Crear',
            'Equipos',
            "Se registró el equipo {$equipo->codigo_inventario} ({$equipo->tipo} {$equipo->marca} {$equipo->modelo})",
            $equipo->id
        );

        return redirect()->route('equipos')->with('success', "Equipo {$equipo->codigo_inventario} ingresado correctamente.");
    }

    public function actualizarEquipo(Request $request, Equipo $equipo)
    {
        $datos = Equipo::validar($request->all(), $equipo->id);
        $equipo->update($datos);

        Bitacora::registrar(
            'Actualizar',
            'Equipos',
            "Se actualizó el equipo {$equipo->codigo_inventario} (Estado: {$equipo->estado})",
            $equipo->id
        );

        return redirect()->route('equipos')->with('success', "Equipo {$equipo->codigo_inventario} actualizado correctamente.");
    }

    public function eliminarEquipo(Equipo $equipo)
    {
        $codigo = $equipo->codigo_inventario;
        $id = $equipo->id;

        $equipo->update([
            'estado' => 'baja_definitiva',
            'eliminado_por' => Auth::id(),
        ]);
        $equipo->delete();

        Bitacora::registrar(
            'Eliminar',
            'Equipos',
            "Se dio de baja definitiva al equipo {$codigo}",
            $id
        );

        return redirect()->route('equipos')->with('success', "Equipo {$codigo} dado de baja correctamente.");
    }
}
