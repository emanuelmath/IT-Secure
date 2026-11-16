<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use App\Models\CentroComputo;
use App\Models\Sede;
use Illuminate\Http\Request;

class CentroComputoController extends Controller
{
    public function index()
    {
        $centros = CentroComputo::with('sede.empresa')
            ->withCount('equipos')
            ->get();

        $sedes = Sede::with('empresa')->get();

        return view('infraestructura.centros', compact('centros', 'sedes'));
    }

    public function store(Request $request)
    {
        $datos = CentroComputo::validar($request->all());
        $centro = CentroComputo::create($datos);

        Bitacora::registrar(
            'Crear',
            'Centros de Cómputo',
            "Se registró el centro {$centro->nombre} (Código: {$centro->codigo})",
            $centro->id
        );

        return redirect()->route('centros')->with('success', 'Centro de cómputo registrado correctamente.');
    }

    public function update(Request $request, CentroComputo $centro)
    {
        $datos = CentroComputo::validar($request->all(), $centro->id);
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
