<?php

namespace App\Http\Controllers;

use App\Models\Sede;
use Illuminate\Http\Request;

class SedeController extends Controller
{
    public function index()
    {
        $datos = [
            "sedes" => Sede::obtenerSedes()
        ];
        return view('infraestructura.sedes', data: $datos);
    }
}
