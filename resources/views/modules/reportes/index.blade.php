@extends('layouts.app')

@section('title', 'Reportes')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="flex items-center gap-2 text-sm text-slate-400">
        <a href="{{ route('dashboard') }}" class="hover:text-slate-600">Inicio</a><span>/</span>
        <span class="text-slate-600 font-medium">Reportes</span>
    </div>

    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Reportes</h1>
            <p class="text-slate-500">Indicadores del taller con datos reales.</p>
        </div>
        <a href="{{ route('reportes.ventas.export', ['desde'=>$desde,'hasta'=>$hasta]) }}" class="rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-emerald-600/30 hover:bg-emerald-700 inline-flex items-center gap-2 shrink-0">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
            Exportar ventas (CSV)
        </a>
    </div>

    <!-- Filtro por rango de fechas -->
    <form method="GET" class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-slate-100 flex flex-col sm:flex-row sm:items-end gap-4">
        <div class="flex-1">
            <label class="block text-xs font-medium text-slate-500 mb-1.5">Desde</label>
            <input type="date" name="desde" value="{{ $desde }}" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-200 outline-none">
        </div>
        <div class="flex-1">
            <label class="block text-xs font-medium text-slate-500 mb-1.5">Hasta</label>
            <input type="date" name="hasta" value="{{ $hasta }}" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-200 outline-none">
        </div>
        <div class="flex gap-2">
            <button type="submit" class="rounded-xl bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-brand-700">Aplicar</button>
            <a href="{{ route('reportes.index') }}" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50">Limpiar</a>
        </div>
    </form>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-100"><p class="text-sm text-slate-500">Ventas del periodo</p><p class="mt-1 text-2xl font-bold text-slate-800">S/ {{ number_format($kpis['ventas_periodo'], 2) }}</p><p class="text-xs text-slate-400 mt-0.5">{{ $kpis['comprobantes'] }} comprobantes</p></div>
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-100"><p class="text-sm text-slate-500">Compras del periodo</p><p class="mt-1 text-2xl font-bold text-slate-800">S/ {{ number_format($kpis['compras_periodo'], 2) }}</p></div>
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-100"><p class="text-sm text-slate-500">Ticket medio</p><p class="mt-1 text-2xl font-bold text-slate-800">S/ {{ number_format($kpis['ticket_medio'], 2) }}</p></div>
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-100"><p class="text-sm text-slate-500">Insumos stock bajo</p><p class="mt-1 text-2xl font-bold text-rose-600">{{ $kpis['insumos_bajo'] }}</p></div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        <div class="rounded-2xl bg-white p-5 sm:p-6 shadow-sm ring-1 ring-slate-100">
            <h3 class="font-semibold text-slate-800 mb-1">Ventas vs. Compras</h3>
            <p class="text-sm text-slate-400 mb-4">Por mes · periodo seleccionado (S/)</p>
            <div class="relative w-full h-64 sm:h-72"><canvas id="rVentasCompras"></canvas></div>
        </div>
        <div class="rounded-2xl bg-white p-5 sm:p-6 shadow-sm ring-1 ring-slate-100">
            <h3 class="font-semibold text-slate-800 mb-1">Productos más vendidos</h3>
            <p class="text-sm text-slate-400 mb-4">Por unidades</p>
            <div class="relative w-full h-64 sm:h-72"><canvas id="rTop"></canvas></div>
        </div>
        <div class="rounded-2xl bg-white p-5 sm:p-6 shadow-sm ring-1 ring-slate-100">
            <h3 class="font-semibold text-slate-800 mb-1">Pedidos por estado</h3>
            <p class="text-sm text-slate-400 mb-4">Distribución actual</p>
            <div class="relative w-full h-64 sm:h-72"><canvas id="rPedidos"></canvas></div>
        </div>
        <div class="rounded-2xl bg-white p-5 sm:p-6 shadow-sm ring-1 ring-slate-100">
            <h3 class="font-semibold text-slate-800 mb-1">Producción por etapa</h3>
            <p class="text-sm text-slate-400 mb-4">Órdenes en cada etapa</p>
            <div class="relative w-full h-64 sm:h-72"><canvas id="rProduccion"></canvas></div>
        </div>
    </div>

    <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-100 overflow-hidden">
        <div class="p-6 pb-4"><h3 class="font-semibold text-slate-800">Insumos con stock bajo</h3></div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="text-xs uppercase text-slate-400 bg-slate-50">
                    <tr>
                        <th class="text-left font-semibold px-6 py-3">Código</th>
                        <th class="text-left font-semibold px-6 py-3">Insumo</th>
                        <th class="text-right font-semibold px-6 py-3">Stock</th>
                        <th class="text-right font-semibold px-6 py-3">Mínimo</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($stockBajo as $m)
                        <tr>
                            <td class="px-6 py-3.5 font-mono text-xs text-slate-500">{{ $m->codigo }}</td>
                            <td class="px-6 py-3.5 font-medium text-slate-700">{{ $m->nombre }}</td>
                            <td class="px-6 py-3.5 text-right"><span class="text-xs font-semibold px-2.5 py-1 rounded-lg bg-rose-50 text-rose-600">{{ rtrim(rtrim(number_format($m->stock,2),'0'),'.') }} {{ $m->unidad }}</span></td>
                            <td class="px-6 py-3.5 text-right text-slate-500">{{ rtrim(rtrim(number_format($m->stock_minimo,2),'0'),'.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-6 py-10 text-center text-slate-400">Ningún insumo por debajo del mínimo. 👍</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    Chart.defaults.font.family = "'Inter', sans-serif";
    const grid = '#eef2f7', axis = '#94a3b8';

    new Chart(document.getElementById('rVentasCompras'), {
        type: 'bar',
        data: {
            labels: @json($labels),
            datasets: [
                { label:'Ventas', data:@json($serieVentas), backgroundColor:'#6366f1', borderRadius:6, maxBarThickness:26 },
                { label:'Compras', data:@json($serieCompras), backgroundColor:'#fb7185', borderRadius:6, maxBarThickness:26 },
            ]
        },
        options: { responsive:true, maintainAspectRatio:false,
            plugins:{ legend:{ position:'bottom', labels:{ usePointStyle:true, boxWidth:8 } } },
            scales:{ y:{ beginAtZero:true, grid:{color:grid}, ticks:{color:axis, callback:v=>'S/ '+(v/1000)+'k'}, border:{display:false} }, x:{ grid:{display:false}, ticks:{color:axis}, border:{display:false} } } }
    });

    new Chart(document.getElementById('rTop'), {
        type: 'bar',
        data: {
            labels: @json($topProductos->pluck('nombre')),
            datasets: [{ label:'Unidades', data:@json($topProductos->pluck('unidades')), backgroundColor:['#4f46e5','#6366f1','#818cf8','#a5b4fc','#c7d2fe'], borderRadius:6, maxBarThickness:24 }]
        },
        options: { indexAxis:'y', responsive:true, maintainAspectRatio:false, plugins:{ legend:{display:false} },
            scales:{ x:{ beginAtZero:true, grid:{color:grid}, ticks:{color:axis}, border:{display:false} }, y:{ grid:{display:false}, ticks:{color:'#475569'}, border:{display:false} } } }
    });

    const pedEstados = @json($pedidosEstado);
    const pedLabels = { pendiente:'Pendiente', en_produccion:'En producción', acabado:'Acabado', entregado:'Entregado', anulado:'Anulado' };
    new Chart(document.getElementById('rPedidos'), {
        type: 'doughnut',
        data: {
            labels: Object.keys(pedEstados).map(k=>pedLabels[k]||k),
            datasets: [{ data:Object.values(pedEstados), backgroundColor:['#94a3b8','#6366f1','#f59e0b','#10b981','#f43f5e'], borderWidth:0, cutout:'65%' }]
        },
        options: { responsive:true, maintainAspectRatio:false, plugins:{ legend:{ position:'bottom', labels:{ usePointStyle:true, boxWidth:8 } } } }
    });

    const prodEtapas = @json($produccionEtapa);
    const etapaLabels = { corte:'Corte', confeccion:'Confección', acabado:'Acabado', control:'Control', terminado:'Terminado' };
    new Chart(document.getElementById('rProduccion'), {
        type: 'bar',
        data: {
            labels: Object.keys(prodEtapas).map(k=>etapaLabels[k]||k),
            datasets: [{ label:'Órdenes', data:Object.values(prodEtapas), backgroundColor:'#8b5cf6', borderRadius:6, maxBarThickness:40 }]
        },
        options: { responsive:true, maintainAspectRatio:false, plugins:{ legend:{display:false} },
            scales:{ y:{ beginAtZero:true, grid:{color:grid}, ticks:{color:axis, precision:0}, border:{display:false} }, x:{ grid:{display:false}, ticks:{color:axis}, border:{display:false} } } }
    });
</script>
@endsection
