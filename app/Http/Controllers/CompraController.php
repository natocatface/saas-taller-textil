<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\MateriaPrima;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CompraController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');
        $estado = $request->input('estado');

        $compras = Compra::query()
            ->with('proveedor')
            ->when($q, fn ($query) => $query->where('codigo', 'like', "%{$q}%")
                ->orWhereHas('proveedor', fn ($p) => $p->where('razon_social', 'like', "%{$q}%")))
            ->when($estado, fn ($query) => $query->where('estado', $estado))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('modules.compras.index', compact('compras', 'q', 'estado'));
    }

    public function create()
    {
        return view('modules.compras.form', [
            'compra' => new Compra(['fecha' => now()->format('Y-m-d'), 'estado' => 'pendiente']),
            'proveedores' => Proveedor::where('estado', true)->orderBy('razon_social')->get(),
            'materias' => MateriaPrima::where('estado', true)->orderBy('nombre')->get(),
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        $q = $request->input('q');
        $estado = $request->input('estado');

        $compras = Compra::query()
            ->with('proveedor')
            ->when($q, fn ($query) => $query->where('codigo', 'like', "%{$q}%")
                ->orWhereHas('proveedor', fn ($p) => $p->where('razon_social', 'like', "%{$q}%")))
            ->when($estado, fn ($query) => $query->where('estado', $estado))
            ->latest()->get();

        $filename = 'compras_'.now()->format('Ymd_His').'.csv';

        return response()->streamDownload(function () use ($compras) {
            $out = fopen('php://output', 'w');
            fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM UTF-8 para Excel
            fputcsv($out, ['Código', 'Fecha', 'Proveedor', 'Estado', 'Stock aplicado', 'Total']);
            foreach ($compras as $c) {
                fputcsv($out, [
                    $c->codigo,
                    optional($c->fecha)->format('d/m/Y'),
                    $c->proveedor->razon_social ?? '',
                    $c->estado,
                    $c->stock_aplicado ? 'Sí' : 'No',
                    number_format($c->total, 2, '.', ''),
                ]);
            }
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        DB::transaction(function () use ($data) {
            $compra = Compra::create([
                'codigo' => $this->nextCodigo(),
                'proveedor_id' => $data['proveedor_id'],
                'fecha' => $data['fecha'],
                'estado' => $data['estado'],
                'observaciones' => $data['observaciones'] ?? null,
                'stock_aplicado' => false,
            ]);
            $this->syncItems($compra, $data['items']);
            if ($compra->estado === 'recibida') {
                $this->aplicarStock($compra);
            }
        });

        return redirect()->route('compras.index')->with('ok', 'Compra registrada correctamente.');
    }

    public function show(Compra $compra)
    {
        $compra->load('proveedor', 'items.materiaPrima');

        return view('modules.compras.show', compact('compra'));
    }

    public function comprobante(Compra $compra)
    {
        $compra->load('proveedor', 'items.materiaPrima');

        return view('modules.compras.comprobante', [
            'compra' => $compra,
            'config' => \App\Models\Configuracion::actual(),
        ]);
    }

    public function pdf(Compra $compra)
    {
        $compra->load('proveedor', 'items.materiaPrima');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.compra', [
            'compra' => $compra,
            'config' => \App\Models\Configuracion::actual(),
        ])->setPaper('a4');

        return $pdf->download($compra->codigo.'.pdf');
    }

    public function edit(Compra $compra)
    {
        $compra->load('items');

        return view('modules.compras.form', [
            'compra' => $compra,
            'proveedores' => Proveedor::where('estado', true)->orderBy('razon_social')->get(),
            'materias' => MateriaPrima::where('estado', true)->orderBy('nombre')->get(),
        ]);
    }

    public function update(Request $request, Compra $compra)
    {
        $data = $this->validateData($request);
        DB::transaction(function () use ($compra, $data) {
            // Revertir stock previo si ya se había aplicado.
            if ($compra->stock_aplicado) {
                $this->revertirStock($compra);
            }
            $compra->items()->delete();
            $compra->update([
                'proveedor_id' => $data['proveedor_id'],
                'fecha' => $data['fecha'],
                'estado' => $data['estado'],
                'observaciones' => $data['observaciones'] ?? null,
            ]);
            $this->syncItems($compra, $data['items']);
            if ($compra->estado === 'recibida') {
                $this->aplicarStock($compra);
            }
        });

        return redirect()->route('compras.index')->with('ok', 'Compra actualizada correctamente.');
    }

    public function destroy(Compra $compra)
    {
        DB::transaction(function () use ($compra) {
            if ($compra->stock_aplicado) {
                $this->revertirStock($compra);
            }
            $compra->delete();
        });

        return redirect()->route('compras.index')->with('ok', 'Compra eliminada.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'proveedor_id' => ['required', 'exists:proveedores,id'],
            'fecha' => ['required', 'date'],
            'estado' => ['required', 'in:'.implode(',', array_keys(Compra::ESTADOS))],
            'observaciones' => ['nullable', 'string', 'max:500'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.materia_prima_id' => ['required', 'exists:materia_primas,id'],
            'items.*.cantidad' => ['required', 'numeric', 'min:0.01'],
            'items.*.costo_unitario' => ['required', 'numeric', 'min:0'],
        ], [
            'items.required' => 'Agregue al menos un insumo a la compra.',
        ]);
    }

    private function syncItems(Compra $compra, array $items): void
    {
        $total = 0;
        foreach ($items as $item) {
            $sub = $item['cantidad'] * $item['costo_unitario'];
            $total += $sub;
            $compra->items()->create([
                'materia_prima_id' => $item['materia_prima_id'],
                'cantidad' => $item['cantidad'],
                'costo_unitario' => $item['costo_unitario'],
                'subtotal' => $sub,
            ]);
        }
        $compra->update(['total' => $total]);
    }

    private function aplicarStock(Compra $compra): void
    {
        foreach ($compra->items()->get() as $item) {
            MateriaPrima::where('id', $item->materia_prima_id)->increment('stock', $item->cantidad);
        }
        $compra->update(['stock_aplicado' => true]);
    }

    private function revertirStock(Compra $compra): void
    {
        foreach ($compra->items()->get() as $item) {
            MateriaPrima::where('id', $item->materia_prima_id)->decrement('stock', $item->cantidad);
        }
        $compra->update(['stock_aplicado' => false]);
    }

    private function nextCodigo(): string
    {
        $next = (int) (Compra::max('id') ?? 0) + 1;

        return 'COM-'.str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }
}
