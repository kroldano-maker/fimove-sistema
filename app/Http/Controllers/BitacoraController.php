<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;

class BitacoraController extends Controller
{
    public function index()
    {
        $registros = Bitacora::with('usuario')
            ->orderByDesc('fecha')
            ->paginate(25);

        return view('bitacora.index', ['registros' => $registros]);
    }
}