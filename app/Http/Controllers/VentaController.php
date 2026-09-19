<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');
        $estado = $request->input('estado');

        $ventas = Venta::query()
            ->with('cliente')
            ->when($q, fn ($query) => $query->where('codigo', 'like', "%{$q}%")
                ->orWhereHas('cliente', fn ($c) => $c->where('nombre', 'like', "%{$q}%")))
            ->when($estado, fn ($query) => $query->where('estado', $estado))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('modules.ventas.index', compact('ventas', 'q', 'estado'));
    }

    public function create()
    {
        return view('modules.ventas.form', [
            'venta' => new Venta(['fecha' => now()->format('Y-m-d')]),
            'clientes' => Cliente::where('estado', true)->orderBy('nombre')->get(),
            'productos' => Producto::where('estado', true)->orderBy('nombre')->get(),
            'pedidos' => Pedido::whereNotIn('estado', ['anulado'])->latest()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        DB::transaction(function () use ($data) {
            $venta = Venta::create([
                'codigo' => $this->nextCodigo(),
                'cliente_id' => $data['cliente_id'],
                'pedido_id' => $data['pedido_id'] ?? null,
                'fecha' => $data['fecha'],
                'tipo_comprobante' => $data['tipo_comprobante'],
                'estado' => $data['estado'],
                'observaciones' => $data['observaciones'] ?? null,
            ]);
            $this->syncItems($venta, $data['items']);
        });

        return redirect()->route('ventas.index')->with('ok', 'Venta registrada correctamente.');
    }

    public function show(Venta $venta)
    {
        $venta->load('cliente', 'items.producto', 'pedido');

        return view('modules.ventas.show', compact('venta'));
    }

    public function comprobante(Venta $venta)
    {
        $venta->load('cliente', 'items.producto');

        return view('modules.ventas.comprobante', [
            'venta' => $venta,
            'config' => \App\Models\Configuracion::actual(),
        ]);
    }

    public function pdf(Venta $venta)
    {
        $venta->load('cliente', 'items.producto');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.venta', [
            'venta' => $venta,
            'config' => \App\Models\Configuracion::actual(),
        ])->setPaper('a4');

        return $pdf->download($venta->codigo.'.pdf');
    }

    public function edit(Venta $venta)
    {
        $venta->load('items');

        return view('modules.ventas.form', [
            'venta' => $venta,
            'clientes' => Cliente::where('estado', true)->orderBy('nombre')->get(),
            'productos' => Producto::where('estado', true)->orderBy('nombre')->get(),
            'pedidos' => Pedido::whereNotIn('estado', ['anulado'])->latest()->get(),
        ]);
    }

    public function update(Request $request, Venta $venta)
    {
        $data = $this->validateData($request);
        DB::transaction(function () use ($venta, $data) {
            $venta->update([
                'cliente_id' => $data['cliente_id'],
                'pedido_id' => $data['pedido_id'] ?? null,
                'fecha' => $data['fecha'],
                'tipo_comprobante' => $data['tipo_comprobante'],
                'estado' => $data['estado'],
                'observaciones' => $data['observaciones'] ?? null,
            ]);
            $venta->items()->delete();
            $this->syncItems($venta, $data['items']);
        });

        return redirect()->route('ventas.index')->with('ok', 'Venta actualizada correctamente.');
    }

    public function destroy(Venta $venta)
    {
        $venta->delete();

        return redirect()->route('ventas.index')->with('ok', 'Venta eliminada.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'cliente_id' => ['required', 'exists:clientes,id'],
            'pedido_id' => ['nullable', 'exists:pedidos,id'],
            'fecha' => ['required', 'date'],
            'tipo_comprobante' => ['required', 'in:boleta,factura'],
            'estado' => ['required', 'in:'.implode(',', array_keys(Venta::ESTADOS))],
            'observaciones' => ['nullable', 'string', 'max:500'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.producto_id' => ['required', 'exists:productos,id'],
            'items.*.cantidad' => ['required', 'integer', 'min:1'],
            'items.*.precio_unitario' => ['required', 'numeric', 'min:0'],
        ], [
            'items.required' => 'Agregue al menos un producto a la venta.',
        ]);
    }

    private function syncItems(Venta $venta, array $items): void
    {
        $subtotal = 0;
        foreach ($items as $item) {
            $sub = $item['cantidad'] * $item['precio_unitario'];
            $subtotal += $sub;
            $venta->items()->create([
                'producto_id' => $item['producto_id'],
                'cantidad' => $item['cantidad'],
                'precio_unitario' => $item['precio_unitario'],
                'subtotal' => $sub,
            ]);
        }
        $igv = round($subtotal * Venta::IGV, 2);
        $venta->update([
            'subtotal' => $subtotal,
            'igv' => $igv,
            'total' => $subtotal + $igv,
        ]);
    }

    private function nextCodigo(): string
    {
        $next = (int) (Venta::max('id') ?? 0) + 1;

        return 'VEN-'.str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }
}
