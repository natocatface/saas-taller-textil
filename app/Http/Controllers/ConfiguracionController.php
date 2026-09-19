<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use Illuminate\Http\Request;

class ConfiguracionController extends Controller
{
    public function index()
    {
        return view('modules.configuracion.index', [
            'config' => Configuracion::actual(),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'empresa' => ['required', 'string', 'max:150'],
            'ruc' => ['nullable', 'string', 'max:20'],
            'direccion' => ['nullable', 'string', 'max:200'],
            'telefono' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:150'],
            'moneda' => ['required', 'string', 'max:10'],
            'igv' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        Configuracion::actual()->update($data);

        return redirect()->route('configuracion.index')->with('ok', 'Configuración guardada correctamente.');
    }
}
