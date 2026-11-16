<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;

class BitacoraController extends Controller
{
    public function index()
    {
        $bitacoras = Bitacora::orderByDesc('id')->limit(500)->get();

        return view('auditoria.bitacoras', compact('bitacoras'));
    }
}
