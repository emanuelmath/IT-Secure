<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use App\Models\Empresa;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    public function index()
    {
        $empresas = Empresa::withCount('sedes')->get();
        return view('infraestructura.empresas', compact('empresas'));
    }

    public function store(Request $request)
    {
        $datos = Empresa::validar($request->all());
        $empresa = Empresa::create($datos);

        Bitacora::registrar('Crear', 'Empresas', "Se registró la empresa {$empresa->nombre} (NIT: {$empresa->nit})", $empresa->id);

        return redirect()->route('empresas')->with('success', 'Empresa registrada correctamente.');
    }

    public function update(Request $request, Empresa $empresa)
    {
        $datos = Empresa::validar($request->all(), $empresa->id);
        $empresa->update($datos);

        Bitacora::registrar('Actualizar', 'Empresas', "Se actualizó la empresa {$empresa->nombre}", $empresa->id);

        return redirect()->route('empresas')->with('success', 'Empresa actualizada correctamente.');
    }

    public function destroy(Empresa $empresa)
    {
        $nombre = $empresa->nombre;
        $id = $empresa->id;
        $empresa->delete();

        Bitacora::registrar('Eliminar', 'Empresas', "Se dio de baja la empresa {$nombre}", $id);

        return redirect()->route('empresas')->with('success', 'Empresa eliminada correctamente.');
    }
}
