<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');
        $clientes = Cliente::query()
            ->when($q, fn ($query) => $query->where('nombre', 'like', "%{$q}%")
                ->orWhere('numero_documento', 'like', "%{$q}%")
                ->orWhere('email', 'like', "%{$q}%"))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('modules.clientes.index', compact('clientes', 'q'));
    }

    public function create()
    {
        return view('modules.clientes.form', ['cliente' => new Cliente()]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        Cliente::create($data);

        return redirect()->route('clientes.index')->with('ok', 'Cliente registrado correctamente.');
    }

    public function edit(Cliente $cliente)
    {
        return view('modules.clientes.form', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        $cliente->update($this->validated($request));

        return redirect()->route('clientes.index')->with('ok', 'Cliente actualizado correctamente.');
    }

    public function destroy(Cliente $cliente)
    {
        $cliente->delete();

        return redirect()->route('clientes.index')->with('ok', 'Cliente eliminado.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:150'],
            'tipo_documento' => ['required', 'in:DNI,RUC,CE'],
            'numero_documento' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:150'],
            'telefono' => ['nullable', 'string', 'max:30'],
            'direccion' => ['nullable', 'string', 'max:200'],
            'ciudad' => ['nullable', 'string', 'max:80'],
            'contacto' => ['nullable', 'string', 'max:120'],
        ]);
        $data['estado'] = $request->boolean('estado');

        return $data;
    }
}
