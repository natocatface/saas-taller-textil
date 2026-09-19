<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PedidoController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');
        $estado = $request->input('estado');

        $pedidos = Pedido::query()
            ->with('cliente')
            ->withCount('items')
            ->when($q, fn ($query) => $query->where('codigo', 'like', "%{$q}%")
                ->orWhereHas('cliente', fn ($c) => $c->where('nombre', 'like', "%{$q}%")))
            ->when($estado, fn ($query) => $query->where('estado', $estado))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('modules.pedidos.index', compact('pedidos', 'q', 'estado'));
    }

    public function create()
    {
        return view('modules.pedidos.form', [
            'pedido' => new Pedido(['fecha_pedido' => now()->format('Y-m-d')]),
            'clientes' => Cliente::where('estado', true)->orderBy('nombre')->get(),
            'productos' => Producto::where('estado', true)->orderBy('nombre')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        DB::transaction(function () use ($data) {
            $pedido = Pedido::create([
                'codigo' => $this->nextCodigo(),
                'cliente_id' => $data['cliente_id'],
                'fecha_pedido' => $data['fecha_pedido'],
                'fecha_entrega' => $data['fecha_entrega'] ?? null,
                'estado' => $data['estado'],
                'observaciones' => $data['observaciones'] ?? null,
                'total' => 0,
            ]);
            $this->syncItems($pedido, $data['items']);
        });

        return redirect()->route('pedidos.index')->with('ok', 'Pedido registrado correctamente.');
    }

    public function show(Pedido $pedido)
    {
        $pedido->load('cliente', 'items.producto', 'ordenesProduccion.empleado');

        return view('modules.pedidos.show', compact('pedido'));
    }

    public function edit(Pedido $pedido)
    {
        $pedido->load('items');

        return view('modules.pedidos.form', [
            'pedido' => $pedido,
            'clientes' => Cliente::where('estado', true)->orderBy('nombre')->get(),
            'productos' => Producto::where('estado', true)->orderBy('nombre')->get(),
        ]);
    }

    public function update(Request $request, Pedido $pedido)
    {
        $data = $this->validateData($request);
        DB::transaction(function () use ($pedido, $data) {
            $pedido->update([
                'cliente_id' => $data['cliente_id'],
                'fecha_pedido' => $data['fecha_pedido'],
                'fecha_entrega' => $data['fecha_entrega'] ?? null,
                'estado' => $data['estado'],
                'observaciones' => $data['observaciones'] ?? null,
            ]);
            $pedido->items()->delete();
            $this->syncItems($pedido, $data['items']);
        });

        return redirect()->route('pedidos.index')->with('ok', 'Pedido actualizado correctamente.');
    }

    public function destroy(Pedido $pedido)
    {
        $pedido->delete();

        return redirect()->route('pedidos.index')->with('ok', 'Pedido eliminado.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'cliente_id' => ['required', 'exists:clientes,id'],
            'fecha_pedido' => ['required', 'date'],
            'fecha_entrega' => ['nullable', 'date', 'after_or_equal:fecha_pedido'],
            'estado' => ['required', 'in:'.implode(',', array_keys(Pedido::ESTADOS))],
            'observaciones' => ['nullable', 'string', 'max:500'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.producto_id' => ['required', 'exists:productos,id'],
            'items.*.cantidad' => ['required', 'integer', 'min:1'],
            'items.*.precio_unitario' => ['required', 'numeric', 'min:0'],
        ], [
            'items.required' => 'Agregue al menos un producto al pedido.',
            'fecha_entrega.after_or_equal' => 'La fecha de entrega no puede ser anterior al pedido.',
        ]);
    }

    private function syncItems(Pedido $pedido, array $items): void
    {
        $total = 0;
        foreach ($items as $item) {
            $subtotal = $item['cantidad'] * $item['precio_unitario'];
            $total += $subtotal;
            $pedido->items()->create([
                'producto_id' => $item['producto_id'],
                'cantidad' => $item['cantidad'],
                'precio_unitario' => $item['precio_unitario'],
                'subtotal' => $subtotal,
            ]);
        }
        $pedido->update(['total' => $total]);
    }

    private function nextCodigo(): string
    {
        $next = (int) (Pedido::max('id') ?? 0) + 1;

        return 'PED-'.str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }
}
