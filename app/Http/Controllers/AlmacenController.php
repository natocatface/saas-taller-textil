<?php

namespace App\Http\Controllers;

use App\Models\MateriaPrima;
use App\Models\MovimientoInventario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlmacenController extends Controller
{
    public function index(Request $request)
    {
        $tipo = $request->input('tipo');
        $materiaId = $request->input('materia');

        $movimientos = MovimientoInventario::query()
            ->with('materiaPrima', 'user')
            ->when($tipo, fn ($q) => $q->where('tipo', $tipo))
            ->when($materiaId, fn ($q) => $q->where('materia_prima_id', $materiaId))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $inicioMes = now()->startOfMonth();
        $resumen = [
            'entradas' => MovimientoInventario::where('tipo', 'entrada')->where('fecha', '>=', $inicioMes)->count(),
            'salidas' => MovimientoInventario::where('tipo', 'salida')->where('fecha', '>=', $inicioMes)->count(),
            'ajustes' => MovimientoInventario::where('tipo', 'ajuste')->where('fecha', '>=', $inicioMes)->count(),
            'stock_bajo' => MateriaPrima::whereColumn('stock', '<=', 'stock_minimo')->count(),
        ];

        return view('modules.almacen.index', [
            'movimientos' => $movimientos,
            'tipo' => $tipo,
            'materiaId' => $materiaId,
            'resumen' => $resumen,
            'materias' => MateriaPrima::orderBy('nombre')->get(),
        ]);
    }

    public function create()
    {
        return view('modules.almacen.form', [
            'materias' => MateriaPrima::where('estado', true)->orderBy('nombre')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'materia_prima_id' => ['required', 'exists:materia_primas,id'],
            'tipo' => ['required', 'in:entrada,salida,ajuste'],
            'cantidad' => ['required', 'numeric', 'min:0'],
            'motivo' => ['nullable', 'string', 'max:150'],
            'referencia' => ['nullable', 'string', 'max:80'],
            'fecha' => ['required', 'date'],
        ]);

        DB::transaction(function () use ($data, $request) {
            $materia = MateriaPrima::lockForUpdate()->findOrFail($data['materia_prima_id']);
            $anterior = (float) $materia->stock;

            $resultante = match ($data['tipo']) {
                'entrada' => $anterior + $data['cantidad'],
                'salida' => $anterior - $data['cantidad'],
                'ajuste' => (float) $data['cantidad'],
            };

            if ($resultante < 0) {
                abort(422, 'La salida supera el stock disponible.');
            }

            $materia->update(['stock' => $resultante]);

            MovimientoInventario::create([
                'materia_prima_id' => $materia->id,
                'tipo' => $data['tipo'],
                'cantidad' => $data['cantidad'],
                'stock_anterior' => $anterior,
                'stock_resultante' => $resultante,
                'motivo' => $data['motivo'] ?? null,
                'referencia' => $data['referencia'] ?? null,
                'user_id' => $request->user()->id,
                'fecha' => $data['fecha'],
            ]);
        });

        return redirect()->route('almacen.index')->with('ok', 'Movimiento registrado y stock actualizado.');
    }

    public function destroy(MovimientoInventario $movimiento)
    {
        DB::transaction(function () use ($movimiento) {
            $materia = MateriaPrima::lockForUpdate()->find($movimiento->materia_prima_id);
            if ($materia) {
                $stock = (float) $materia->stock;
                $nuevo = match ($movimiento->tipo) {
                    'entrada' => $stock - $movimiento->cantidad,
                    'salida' => $stock + $movimiento->cantidad,
                    'ajuste' => (float) $movimiento->stock_anterior,
                };
                $materia->update(['stock' => max($nuevo, 0)]);
            }
            $movimiento->delete();
        });

        return redirect()->route('almacen.index')->with('ok', 'Movimiento revertido y eliminado.');
    }
}
