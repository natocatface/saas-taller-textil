<?php

namespace App\Http\Controllers;

use App\Models\MateriaPrima;
use App\Models\OrdenProduccion;
use App\Models\Pedido;
use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReporteController extends Controller
{
    public function index(Request $request)
    {
        [$desde, $hasta] = $this->rango($request);

        // Lista de meses dentro del rango (máx. 24)
        $mesesList = [];
        $cursor = $desde->copy()->startOfMonth();
        $fin = $hasta->copy()->startOfMonth();
        while ($cursor <= $fin && count($mesesList) < 24) {
            $mesesList[] = $cursor->copy();
            $cursor->addMonth();
        }
        $labels = array_map(fn ($m) => ucfirst($m->translatedFormat('M y')), $mesesList);
        $claves = array_map(fn ($m) => $m->format('Y-m'), $mesesList);

        $ventasMap = $this->porMes(Venta::query()->where('estado', '!=', 'anulado'), 'fecha', 'total', $desde, $hasta);
        $comprasMap = $this->porMes(DB::table('compras')->where('estado', '!=', 'anulada'), 'fecha', 'total', $desde, $hasta);

        $serieVentas = array_map(fn ($k) => round($ventasMap[$k] ?? 0, 2), $claves);
        $serieCompras = array_map(fn ($k) => round($comprasMap[$k] ?? 0, 2), $claves);

        // Top productos vendidos en el rango
        $topProductos = DB::table('venta_items')
            ->join('productos', 'productos.id', '=', 'venta_items.producto_id')
            ->join('ventas', 'ventas.id', '=', 'venta_items.venta_id')
            ->whereBetween('ventas.fecha', [$desde, $hasta])
            ->where('ventas.estado', '!=', 'anulado')
            ->select('productos.nombre', DB::raw('SUM(venta_items.cantidad) as unidades'))
            ->groupBy('productos.nombre')->orderByDesc('unidades')->limit(5)->get();

        // Pedidos por estado (dentro del rango, por fecha de pedido)
        $pedidosEstado = Pedido::whereBetween('fecha_pedido', [$desde, $hasta])
            ->select('estado', DB::raw('count(*) as total'))->groupBy('estado')->pluck('total', 'estado');

        // Producción por etapa (dentro del rango, por fecha de inicio)
        $produccionEtapa = OrdenProduccion::whereBetween('fecha_inicio', [$desde, $hasta])
            ->select('etapa', DB::raw('count(*) as total'))->groupBy('etapa')->pluck('total', 'etapa');

        $stockBajo = MateriaPrima::whereColumn('stock', '<=', 'stock_minimo')->orderBy('stock')->get();

        // KPIs del periodo
        $ventasPeriodo = (float) Venta::where('estado', '!=', 'anulado')->whereBetween('fecha', [$desde, $hasta])->sum('total');
        $comprobantes = Venta::where('estado', '!=', 'anulado')->whereBetween('fecha', [$desde, $hasta])->count();
        $kpis = [
            'ventas_periodo' => $ventasPeriodo,
            'compras_periodo' => (float) DB::table('compras')->where('estado', '!=', 'anulada')->whereBetween('fecha', [$desde, $hasta])->sum('total'),
            'comprobantes' => $comprobantes,
            'ticket_medio' => $comprobantes > 0 ? $ventasPeriodo / $comprobantes : 0,
            'insumos_bajo' => $stockBajo->count(),
        ];

        return view('modules.reportes.index', compact(
            'labels', 'serieVentas', 'serieCompras', 'topProductos',
            'pedidosEstado', 'produccionEtapa', 'stockBajo', 'kpis'
        ) + [
            'desde' => $desde->format('Y-m-d'),
            'hasta' => $hasta->format('Y-m-d'),
        ]);
    }

    public function exportVentas(Request $request): StreamedResponse
    {
        [$desde, $hasta] = $this->rango($request);

        $ventas = Venta::with('cliente')
            ->whereBetween('fecha', [$desde, $hasta])
            ->latest()->get();
        $filename = 'ventas_'.$desde->format('Ymd').'_'.$hasta->format('Ymd').'.csv';

        return response()->streamDownload(function () use ($ventas) {
            $out = fopen('php://output', 'w');
            fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM UTF-8 para Excel
            fputcsv($out, ['Código', 'Fecha', 'Cliente', 'Comprobante', 'Estado', 'Subtotal', 'IGV', 'Total']);
            foreach ($ventas as $v) {
                fputcsv($out, [
                    $v->codigo,
                    optional($v->fecha)->format('d/m/Y'),
                    $v->cliente->nombre ?? '',
                    $v->tipo_comprobante,
                    $v->estado,
                    number_format($v->subtotal, 2, '.', ''),
                    number_format($v->igv, 2, '.', ''),
                    number_format($v->total, 2, '.', ''),
                ]);
            }
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    /** Devuelve [desde, hasta] como Carbon, con defaults de últimos 6 meses. */
    private function rango(Request $request): array
    {
        $hasta = $request->filled('hasta')
            ? Carbon::parse($request->input('hasta'))->endOfDay()
            : Carbon::now()->endOfDay();

        $desde = $request->filled('desde')
            ? Carbon::parse($request->input('desde'))->startOfDay()
            : Carbon::now()->subMonths(5)->startOfMonth();

        if ($desde->gt($hasta)) {
            [$desde, $hasta] = [$hasta->copy()->startOfDay(), $desde->copy()->endOfDay()];
        }

        return [$desde, $hasta];
    }

    private function porMes($query, string $campoFecha, string $campoSuma, Carbon $desde, Carbon $hasta): array
    {
        return $query
            ->selectRaw("DATE_FORMAT({$campoFecha}, '%Y-%m') as ym, SUM({$campoSuma}) as suma")
            ->whereBetween($campoFecha, [$desde, $hasta])
            ->groupBy('ym')->pluck('suma', 'ym')->toArray();
    }
}
