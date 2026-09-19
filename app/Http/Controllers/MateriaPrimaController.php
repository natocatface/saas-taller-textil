<?php

namespace App\Http\Controllers;

use App\Models\MateriaPrima;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MateriaPrimaController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');
        $materias = MateriaPrima::query()
            ->with('proveedor')
            ->when($q, fn ($query) => $query->where('nombre', 'like', "%{$q}%")
                ->orWhere('codigo', 'like', "%{$q}%"))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('modules.materiaprima.index', compact('materias', 'q'));
    }

    public function create()
    {
        return view('modules.materiaprima.form', [
            'materia' => new MateriaPrima(),
            'proveedores' => Proveedor::where('estado', true)->orderBy('razon_social')->get(),
        ]);
    }

    public function store(Request $request)
    {
        MateriaPrima::create($this->validated($request));

        return redirect()->route('materiaprima.index')->with('ok', 'Insumo registrado correctamente.');
    }

    public function edit(MateriaPrima $materiaprima)
    {
        return view('modules.materiaprima.form', [
            'materia' => $materiaprima,
            'proveedores' => Proveedor::where('estado', true)->orderBy('razon_social')->get(),
        ]);
    }

    public function update(Request $request, MateriaPrima $materiaprima)
    {
        $materiaprima->update($this->validated($request, $materiaprima->id));

        return redirect()->route('materiaprima.index')->with('ok', 'Insumo actualizado correctamente.');
    }

    public function destroy(MateriaPrima $materiaprima)
    {
        $materiaprima->delete();

        return redirect()->route('materiaprima.index')->with('ok', 'Insumo eliminado.');
    }

    private function validated(Request $request, ?int $id = null): array
    {
        $data = $request->validate([
            'codigo' => ['required', 'string', 'max:40', Rule::unique('materia_primas', 'codigo')->ignore($id)],
            'nombre' => ['required', 'string', 'max:150'],
            'tipo' => ['required', 'in:tela,hilo,avio,otro'],
            'unidad' => ['required', 'string', 'max:30'],
            'stock' => ['required', 'numeric', 'min:0'],
            'stock_minimo' => ['required', 'numeric', 'min:0'],
            'costo_unitario' => ['required', 'numeric', 'min:0'],
            'proveedor_id' => ['nullable', 'exists:proveedores,id'],
        ]);
        $data['estado'] = $request->boolean('estado');

        return $data;
    }
}
