<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');
        $productos = Producto::query()
            ->when($q, fn ($query) => $query->where('nombre', 'like', "%{$q}%")
                ->orWhere('codigo', 'like', "%{$q}%")
                ->orWhere('categoria', 'like', "%{$q}%"))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('modules.productos.index', compact('productos', 'q'));
    }

    public function create()
    {
        return view('modules.productos.form', ['producto' => new Producto()]);
    }

    public function store(Request $request)
    {
        Producto::create($this->validated($request));

        return redirect()->route('productos.index')->with('ok', 'Producto registrado correctamente.');
    }

    public function edit(Producto $producto)
    {
        return view('modules.productos.form', compact('producto'));
    }

    public function update(Request $request, Producto $producto)
    {
        $producto->update($this->validated($request, $producto->id));

        return redirect()->route('productos.index')->with('ok', 'Producto actualizado correctamente.');
    }

    public function destroy(Producto $producto)
    {
        $producto->delete();

        return redirect()->route('productos.index')->with('ok', 'Producto eliminado.');
    }

    private function validated(Request $request, ?int $id = null): array
    {
        $data = $request->validate([
            'codigo' => ['required', 'string', 'max:40', Rule::unique('productos', 'codigo')->ignore($id)],
            'nombre' => ['required', 'string', 'max:150'],
            'categoria' => ['nullable', 'string', 'max:80'],
            'talla' => ['nullable', 'string', 'max:30'],
            'color' => ['nullable', 'string', 'max:40'],
            'precio' => ['required', 'numeric', 'min:0'],
            'costo' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
        ]);
        $data['estado'] = $request->boolean('estado');

        return $data;
    }
}
