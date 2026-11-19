<?php

namespace App\Http\Controllers;

use App\Http\Requests\SedeRequest;
use App\Models\Bitacora;
use App\Models\Empresa;
use App\Models\Sede;
use Illuminate\Http\Request;

class SedeController extends Controller
{
    public function index()
    {
        $datos = [
            'sedes' => Sede::with('empresa')->withCount('centrosDeComputo')->get(),
            'empresas' => Empresa::obtenerEmpresas(),
        ];

        return view('infraestructura.sedes', data: $datos);
    }

    public function store(SedeRequest $request)
    {
        $datos = $request->validated();
        $sede = Sede::create($datos);

        Bitacora::registrar(
            'Crear',
            'Sedes',
            "Se registró la sede {$sede->nombre} (Empresa ID: {$sede->empresa_id})",
            $sede->id
        );

        return redirect()->route('sedes')->with('success', 'Sede registrada correctamente.');
    }

    public function update(SedeRequest $request, Sede $sede)
    {
        $datos = $request->validated();
        $sede->update($datos);

        Bitacora::registrar(
            'Actualizar',
            'Sedes',
            "Se actualizó la sede {$sede->nombre}",
            $sede->id
        );

        return redirect()->route('sedes')->with('success', 'Sede actualizada correctamente.');
    }

    public function destroy(Sede $sede)
    {
        $nombre = $sede->nombre;
        $id = $sede->id;
        $sede->delete();

        Bitacora::registrar(
            'Eliminar',
            'Sedes',
            "Se dio de baja la sede {$nombre}",
            $id
        );

        return redirect()->route('sedes')->with('success', 'Sede eliminada correctamente.');
    }
}
