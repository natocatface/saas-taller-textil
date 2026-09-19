<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\OrdenProduccion;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Http\Request;

class ProduccionController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');
        $etapa = $request->input('etapa');

        $ordenes = OrdenProduccion::query()
            ->with('producto', 'empleado', 'pedido')
            ->when($q, fn ($query) => $query->where('codigo', 'like', "%{$q}%")
                ->orWhereHas('producto', fn ($p) => $p->where('nombre', 'like', "%{$q}%")))
            ->when($etapa, fn ($query) => $query->where('etapa', $etapa))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // Resumen por etapa para el tablero
        $resumen = OrdenProduccion::selectRaw('etapa, count(*) as total')
            ->where('estado', '!=', 'terminado')
            ->groupBy('etapa')
            ->pluck('total', 'etapa');

        return view('modules.produccion.index', compact('ordenes', 'q', 'etapa', 'resumen'));
    }

    public function create()
    {
        return view('modules.produccion.form', $this->formData(new OrdenProduccion([
            'fecha_inicio' => now()->format('Y-m-d'),
            'avance' => 0,
            'etapa' => 'corte',
            'estado' => 'en_proceso',
        ])));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['codigo'] = $this->nextCodigo();
        OrdenProduccion::create($data);

        return redirect()->route('produccion.index')->with('ok', 'Orden de producción creada.');
    }

    public function edit(OrdenProduccion $produccion)
    {
        return view('modules.produccion.form', $this->formData($produccion));
    }

    public function update(Request $request, OrdenProduccion $produccion)
    {
        $produccion->update($this->validateData($request));

        return redirect()->route('produccion.index')->with('ok', 'Orden actualizada.');
    }

    public function destroy(OrdenProduccion $produccion)
    {
        $produccion->delete();

        return redirect()->route('produccion.index')->with('ok', 'Orden eliminada.');
    }

    private function formData(OrdenProduccion $orden): array
    {
        return [
            'orden' => $orden,
            'productos' => Producto::where('estado', true)->orderBy('nombre')->get(),
            'empleados' => Empleado::where('estado', true)->orderBy('nombre')->get(),
            'pedidos' => Pedido::whereNotIn('estado', ['entregado', 'anulado'])->latest()->get(),
        ];
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'pedido_id' => ['nullable', 'exists:pedidos,id'],
            'producto_id' => ['required', 'exists:productos,id'],
            'empleado_id' => ['nullable', 'exists:empleados,id'],
            'cantidad' => ['required', 'integer', 'min:1'],
            'etapa' => ['required', 'in:'.implode(',', array_keys(OrdenProduccion::ETAPAS))],
            'avance' => ['required', 'integer', 'min:0', 'max:100'],
            'estado' => ['required', 'in:'.implode(',', array_keys(OrdenProduccion::ESTADOS))],
            'fecha_inicio' => ['nullable', 'date'],
            'fecha_fin' => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
            'observaciones' => ['nullable', 'string', 'max:500'],
        ]);
    }

    private function nextCodigo(): string
    {
        $next = (int) (OrdenProduccion::max('id') ?? 0) + 1;

        return 'OP-'.str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }
}
