<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');
        $proveedores = Proveedor::query()
            ->when($q, fn ($query) => $query->where('razon_social', 'like', "%{$q}%")
                ->orWhere('ruc', 'like', "%{$q}%"))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('modules.proveedores.index', compact('proveedores', 'q'));
    }

    public function create()
    {
        return view('modules.proveedores.form', ['proveedor' => new Proveedor()]);
    }

    public function store(Request $request)
    {
        Proveedor::create($this->validated($request));

        return redirect()->route('proveedores.index')->with('ok', 'Proveedor registrado correctamente.');
    }

    public function edit(Proveedor $proveedor)
    {
        return view('modules.proveedores.form', compact('proveedor'));
    }

    public function update(Request $request, Proveedor $proveedor)
    {
        $proveedor->update($this->validated($request));

        return redirect()->route('proveedores.index')->with('ok', 'Proveedor actualizado correctamente.');
    }

    public function destroy(Proveedor $proveedor)
    {
        $proveedor->delete();

        return redirect()->route('proveedores.index')->with('ok', 'Proveedor eliminado.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'razon_social' => ['required', 'string', 'max:150'],
            'ruc' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:150'],
            'telefono' => ['nullable', 'string', 'max:30'],
            'direccion' => ['nullable', 'string', 'max:200'],
            'contacto' => ['nullable', 'string', 'max:120'],
            'tipo' => ['required', 'in:nacional,importado'],
            'condicion_pago' => ['nullable', 'string', 'max:80'],
        ]);
        $data['estado'] = $request->boolean('estado');

        return $data;
    }
}
