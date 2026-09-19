<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Compra;
use App\Models\MateriaPrima;
use App\Models\OrdenProduccion;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\Venta;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // --- Últimos 6 meses ---
        $meses = collect(range(5, 0))->map(fn ($i) => now()->subMonths($i));
        $labels = $meses->map(fn ($m) => ucfirst($m->translatedFormat('M')))->all();
        $claves = $meses->map(fn ($m) => $m->format('Y-m'))->all();

        // --- KPIs ---
        $ventasMes = (float) Venta::where('estado', '!=', 'anulado')
            ->whereMonth('fecha', now()->month)->whereYear('fecha', now()->year)->sum('total');
        $comprobantesMes = Venta::where('estado', '!=', 'anulado')
            ->whereMonth('fecha', now()->month)->whereYear('fecha', now()->year)->count();

        $kpis = [
            'ventas_hoy' => (float) Venta::where('estado', '!=', 'anulado')->whereDate('fecha', today())->sum('total'),
            'ventas_mes' => $ventasMes,
            'comprobantes_mes' => $comprobantesMes,
            'clientes' => Cliente::count(),
            'productos' => Producto::count(),
            'stock_bajo' => MateriaPrima::whereColumn('stock', '<=', 'stock_minimo')->count(),
            'en_produccion' => OrdenProduccion::where('estado', 'en_proceso')->count(),
            'pedidos_activos' => Pedido::whereNotIn('estado', ['entregado', 'anulado'])->count(),
            'ticket_medio' => $comprobantesMes > 0 ? $ventasMes / $comprobantesMes : 0,
        ];

        // --- Series de gráficos ---
        $prodMap = $this->porMes(OrdenProduccion::query(), 'fecha_inicio', 'cantidad');
        $ventasMap = $this->porMes(Venta::query()->where('estado', '!=', 'anulado'), 'fecha', 'total');
        $comprasMap = $this->porMes(Compra::query()->where('estado', '!=', 'anulada'), 'fecha', 'total');

        $serieProduccion = array_map(fn ($k) => round($prodMap[$k] ?? 0), $claves);
        $serieIngresos = array_map(fn ($k) => round($ventasMap[$k] ?? 0, 2), $claves);
        $serieCostos = array_map(fn ($k) => round($comprasMap[$k] ?? 0, 2), $claves);

        // Estado de pedidos (respetando el orden del modelo)
        $estadoCount = Pedido::select('estado', DB::raw('count(*) as total'))->groupBy('estado')->pluck('total', 'estado');
        $estadoPedidos = collect(Pedido::ESTADOS)->map(fn ($lbl, $key) => (int) ($estadoCount[$key] ?? 0));

        // Top productos vendidos
        $topProductos = DB::table('venta_items')
            ->join('productos', 'productos.id', '=', 'venta_items.producto_id')
            ->select('productos.nombre', DB::raw('SUM(venta_items.cantidad) as unidades'))
            ->groupBy('productos.nombre')->orderByDesc('unidades')->limit(5)->get();

        // --- Tablas ---
        $pedidosRecientes = Pedido::with('cliente')->withCount('items')->latest()->limit(5)->get();
        $ordenesProceso = OrdenProduccion::with('producto')->where('estado', 'en_proceso')
            ->orderByDesc('avance')->limit(4)->get();
        $eficiencia = (float) (OrdenProduccion::where('estado', 'en_proceso')->avg('avance') ?? 0);

        return view('dashboard.index', compact(
            'labels', 'kpis', 'serieProduccion', 'serieIngresos', 'serieCostos',
            'estadoPedidos', 'topProductos', 'pedidosRecientes', 'ordenesProceso', 'eficiencia'
        ));
    }

    private function porMes($query, string $campoFecha, string $campoSuma): array
    {
        return $query
            ->selectRaw("DATE_FORMAT({$campoFecha}, '%Y-%m') as ym, SUM({$campoSuma}) as suma")
            ->where($campoFecha, '>=', Carbon::now()->subMonths(6)->startOfMonth())
            ->groupBy('ym')->pluck('suma', 'ym')->toArray();
    }
}
