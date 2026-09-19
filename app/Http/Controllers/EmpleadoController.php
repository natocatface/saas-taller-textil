<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use Illuminate\Http\Request;

class EmpleadoController extends Controller
{
    public array $areas = ['Corte', 'Confección', 'Acabado', 'Control de Calidad', 'Administración', 'Almacén'];

    public function index(Request $request)
    {
        $q = $request->input('q');
        $empleados = Empleado::query()
            ->when($q, fn ($query) => $query->where('nombre', 'like', "%{$q}%")
                ->orWhere('dni', 'like', "%{$q}%")
                ->orWhere('cargo', 'like', "%{$q}%"))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('modules.empleados.index', compact('empleados', 'q'));
    }

    public function create()
    {
        return view('modules.empleados.form', ['empleado' => new Empleado(), 'areas' => $this->areas]);
    }

    public function store(Request $request)
    {
        Empleado::create($this->validated($request));

        return redirect()->route('empleados.index')->with('ok', 'Empleado registrado correctamente.');
    }

    public function edit(Empleado $empleado)
    {
        return view('modules.empleados.form', compact('empleado') + ['areas' => $this->areas]);
    }

    public function update(Request $request, Empleado $empleado)
    {
        $empleado->update($this->validated($request));

        return redirect()->route('empleados.index')->with('ok', 'Empleado actualizado correctamente.');
    }

    public function destroy(Empleado $empleado)
    {
        $empleado->delete();

        return redirect()->route('empleados.index')->with('ok', 'Empleado eliminado.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:150'],
            'dni' => ['nullable', 'string', 'max:15'],
            'cargo' => ['nullable', 'string', 'max:80'],
            'area' => ['required', 'in:'.implode(',', $this->areas)],
            'telefono' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:150'],
            'fecha_ingreso' => ['nullable', 'date'],
            'salario' => ['required', 'numeric', 'min:0'],
        ]);
        $data['estado'] = $request->boolean('estado');

        return $data;
    }
}
