@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Encabezado -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Hola, {{ explode(' ', auth()->user()->name)[0] }} 👋</h1>
            <p class="text-slate-500">Este es el resumen de tu taller para hoy, {{ now()->translatedFormat('d \d\e F Y') }}.</p>
        </div>
        <div class="flex gap-2">
            <button class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50">Exportar</button>
            <a href="{{ route('pedidos.index') }}" class="rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-brand-600/30 hover:bg-brand-700">+ Nuevo pedido</a>
        </div>
    </div>

    <!-- KPIs (estilo tarjetas de color) -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        @php
            $kpis = [
                ['label'=>'Ventas hoy','value'=>'S/ '.number_format($kpis['ventas_hoy'], 2),'sub'=>'Ingresos del día','grad'=>'from-violet-500 to-purple-600','icon'=>'M2.25 18.75a60.07 60.07 0 0115.797 2.101M12 6.75a3 3 0 100 6 3 3 0 000-6z'],
                ['label'=>'Ventas del mes','value'=>'S/ '.number_format($kpis['ventas_mes'], 2),'sub'=>$kpis['comprobantes_mes'].' comprobantes','grad'=>'from-emerald-500 to-teal-600','icon'=>'M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z'],
                ['label'=>'Clientes','value'=>number_format($kpis['clientes']),'sub'=>'Registrados','grad'=>'from-sky-500 to-cyan-600','icon'=>'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0z'],
                ['label'=>'Productos','value'=>number_format($kpis['productos']),'sub'=>'En catálogo','grad'=>'from-fuchsia-500 to-purple-600','icon'=>'M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9'],
                ['label'=>'Stock bajo','value'=>number_format($kpis['stock_bajo']),'sub'=>'Insumos en alerta','grad'=>'from-amber-500 to-orange-600','icon'=>'M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z'],
                ['label'=>'En producción','value'=>number_format($kpis['en_produccion']),'sub'=>'Órdenes activas','grad'=>'from-rose-500 to-red-600','icon'=>'M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281zM15 12a3 3 0 11-6 0 3 3 0 016 0z'],
                ['label'=>'Pedidos activos','value'=>number_format($kpis['pedidos_activos']),'sub'=>'En cartera','grad'=>'from-pink-500 to-rose-600','icon'=>'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                ['label'=>'Ticket medio','value'=>'S/ '.number_format($kpis['ticket_medio'], 2),'sub'=>'Promedio por venta','grad'=>'from-teal-500 to-emerald-600','icon'=>'M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H9v1.5H7.5v1.5H6v1.5H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z'],
            ];
        @endphp
        @foreach ($kpis as $k)
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br {{ $k['grad'] }} p-5 text-white shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition">
                <svg class="absolute -right-3 -bottom-3 h-24 w-24 text-white/10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.4"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $k['icon'] }}"/></svg>
                <div class="relative">
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-white/80">{{ $k['label'] }}</p>
                    <p class="mt-2 text-2xl sm:text-3xl font-extrabold leading-tight">{{ $k['value'] }}</p>
                    <p class="mt-1 text-xs text-white/80">{{ $k['sub'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Gráficos estadísticos (4 paneles, responsive 2x2) -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

        <!-- 1. Producción mensual (barras) -->
        <div class="rounded-2xl bg-white p-5 sm:p-6 shadow-sm ring-1 ring-slate-100">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h3 class="font-semibold text-slate-800">Producción mensual</h3>
                    <p class="text-sm text-slate-400">Prendas terminadas · últimos 6 meses</p>
                </div>
                <span class="text-xs font-semibold px-2.5 py-1 rounded-lg bg-brand-50 text-brand-600">+8%</span>
            </div>
            <div class="relative w-full h-64 sm:h-72"><canvas id="chartProduccion"></canvas></div>
        </div>

        <!-- 2. Ingresos vs. Costos (área) -->
        <div class="rounded-2xl bg-white p-5 sm:p-6 shadow-sm ring-1 ring-slate-100">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h3 class="font-semibold text-slate-800">Ingresos vs. Costos</h3>
                    <p class="text-sm text-slate-400">Comparativo mensual (S/)</p>
                </div>
                <div class="flex gap-3 text-xs">
                    <span class="flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span>Ingresos</span>
                    <span class="flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-rose-400"></span>Costos</span>
                </div>
            </div>
            <div class="relative w-full h-64 sm:h-72"><canvas id="chartIngresos"></canvas></div>
        </div>

        <!-- 3. Estado de pedidos (dona) -->
        <div class="rounded-2xl bg-white p-5 sm:p-6 shadow-sm ring-1 ring-slate-100">
            <div class="mb-4">
                <h3 class="font-semibold text-slate-800">Estado de pedidos</h3>
                <p class="text-sm text-slate-400">Distribución actual</p>
            </div>
            <div class="flex flex-col sm:flex-row items-center gap-6">
                <div class="relative w-48 h-48 shrink-0"><canvas id="chartEstado"></canvas></div>
                <div class="flex-1 w-full space-y-2.5 text-sm">
                    @php $estadoDots = ['bg-slate-400','bg-brand-500','bg-amber-500','bg-emerald-500','bg-rose-500']; @endphp
                    @foreach (\App\Models\Pedido::ESTADOS as $slug => $lbl)
                        <div class="flex items-center justify-between">
                            <span class="flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full {{ $estadoDots[$loop->index % 5] }}"></span>{{ $lbl }}</span>
                            <span class="font-semibold text-slate-700">{{ $estadoPedidos[$slug] ?? 0 }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- 4. Top productos (barras horizontales) -->
        <div class="rounded-2xl bg-white p-5 sm:p-6 shadow-sm ring-1 ring-slate-100">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h3 class="font-semibold text-slate-800">Productos más vendidos</h3>
                    <p class="text-sm text-slate-400">Unidades · este mes</p>
                </div>
                <a href="{{ route('productos.index') }}" class="text-sm font-medium text-brand-600 hover:text-brand-700">Ver catálogo</a>
            </div>
            <div class="relative w-full h-64 sm:h-72"><canvas id="chartTop"></canvas></div>
        </div>
    </div>

    <!-- Pedidos recientes + Estado de producción -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <div class="lg:col-span-2 rounded-2xl bg-white shadow-sm ring-1 ring-slate-100 overflow-hidden">
            <div class="flex items-center justify-between p-6 pb-4">
                <h3 class="font-semibold text-slate-800">Pedidos recientes</h3>
                <a href="{{ route('pedidos.index') }}" class="text-sm font-medium text-brand-600 hover:text-brand-700">Ver todos</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="text-xs uppercase text-slate-400 bg-slate-50">
                        <tr>
                            <th class="text-left font-semibold px-6 py-3">Pedido</th>
                            <th class="text-left font-semibold px-6 py-3">Cliente</th>
                            <th class="text-left font-semibold px-6 py-3">Prendas</th>
                            <th class="text-left font-semibold px-6 py-3">Estado</th>
                            <th class="text-right font-semibold px-6 py-3">Monto</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($pedidosRecientes as $p)
                            <tr class="hover:bg-slate-50 cursor-pointer" onclick="window.location='{{ route('pedidos.show', $p) }}'">
                                <td class="px-6 py-3.5 font-semibold text-brand-600">{{ $p->codigo }}</td>
                                <td class="px-6 py-3.5 text-slate-600">{{ $p->cliente->nombre ?? '—' }}</td>
                                <td class="px-6 py-3.5 text-slate-600">{{ $p->totalPrendas() }}</td>
                                <td class="px-6 py-3.5"><span class="text-xs font-semibold px-2.5 py-1 rounded-lg {{ $p->estadoColor() }}">{{ $p->estadoLabel() }}</span></td>
                                <td class="px-6 py-3.5 text-right font-semibold text-slate-700">S/ {{ number_format($p->total, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-6 py-10 text-center text-slate-400">Aún no hay pedidos registrados.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100">
            <h3 class="font-semibold text-slate-800 mb-4">Órdenes en proceso</h3>
            <div class="space-y-5">
                @php $barras = ['bg-brand-500','bg-emerald-500','bg-amber-500','bg-rose-500']; @endphp
                @forelse ($ordenesProceso as $idx => $o)
                    <div>
                        <div class="flex justify-between text-sm mb-1.5">
                            <span class="text-slate-600 font-medium truncate pr-2">{{ $o->codigo }} · {{ $o->producto->nombre ?? '—' }}</span>
                            <span class="text-slate-500 shrink-0">{{ $o->avance }}%</span>
                        </div>
                        <div class="h-2 rounded-full bg-slate-100 overflow-hidden">
                            <div class="h-full rounded-full {{ $barras[$idx % 4] }}" style="width: {{ $o->avance }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-400 py-4 text-center">No hay órdenes en proceso.</p>
                @endforelse
            </div>
            <div class="mt-6 rounded-xl bg-slate-50 p-4">
                <p class="text-sm text-slate-500">Avance promedio en proceso</p>
                <p class="text-2xl font-bold text-slate-800">{{ number_format($eficiencia, 1) }}%</p>
                <p class="text-xs text-slate-400 mt-1">Sobre las órdenes actualmente en producción</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    Chart.defaults.font.family = "'Inter', sans-serif";
    const grid = '#eef2f7', axis = '#94a3b8';
    const baseScales = {
        y:{ beginAtZero:true, grid:{ color:grid }, ticks:{ color:axis }, border:{ display:false } },
        x:{ grid:{ display:false }, ticks:{ color:axis }, border:{ display:false } }
    };
    const money = (v)=> 'S/ ' + v.toLocaleString('es-PE');

    // 1) Producción mensual — barras con esquinas redondeadas
    new Chart(document.getElementById('chartProduccion'), {
        type: 'bar',
        data: {
            labels: @json($labels),
            datasets: [{
                label:'Prendas', data:@json($serieProduccion),
                backgroundColor:(ctx)=>{
                    const {ctx:c, chartArea} = ctx.chart;
                    if(!chartArea) return '#6366f1';
                    const g = c.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                    g.addColorStop(0,'#a5b4fc'); g.addColorStop(1,'#4f46e5');
                    return g;
                },
                borderRadius:8, borderSkipped:false, maxBarThickness:38
            }]
        },
        options: {
            responsive:true, maintainAspectRatio:false,
            plugins:{ legend:{ display:false }, tooltip:{ callbacks:{ label:(c)=> c.parsed.y.toLocaleString('es-PE')+' prendas' } } },
            scales: baseScales
        }
    });

    // 2) Ingresos vs. Costos — área suavizada
    new Chart(document.getElementById('chartIngresos'), {
        type: 'line',
        data: {
            labels: @json($labels),
            datasets: [
                { label:'Ingresos', data:@json($serieIngresos),
                  borderColor:'#10b981', backgroundColor:'rgba(16,185,129,.12)', fill:true, tension:.4, borderWidth:3, pointRadius:0, pointHoverRadius:5 },
                { label:'Costos', data:@json($serieCostos),
                  borderColor:'#fb7185', backgroundColor:'rgba(251,113,133,.08)', fill:true, tension:.4, borderWidth:3, pointRadius:0, pointHoverRadius:5 },
            ]
        },
        options: {
            responsive:true, maintainAspectRatio:false,
            plugins:{ legend:{ display:false }, tooltip:{ callbacks:{ label:(c)=> c.dataset.label+': '+money(c.parsed.y) } } },
            scales:{ ...baseScales, y:{ ...baseScales.y, ticks:{ color:axis, callback:(v)=> 'S/ '+(v/1000)+'k' } } }
        }
    });

    // 3) Estado de pedidos — dona
    new Chart(document.getElementById('chartEstado'), {
        type: 'doughnut',
        data: {
            labels: @json(array_values(\App\Models\Pedido::ESTADOS)),
            datasets: [{ data:@json($estadoPedidos->values()), backgroundColor:['#94a3b8','#6366f1','#f59e0b','#10b981','#f43f5e'], borderWidth:0, cutout:'68%' }]
        },
        options: {
            responsive:true, maintainAspectRatio:false,
            plugins:{ legend:{ display:false }, tooltip:{ callbacks:{ label:(c)=> c.label+': '+c.parsed } } }
        }
    });

    // 4) Productos más vendidos — barras horizontales
    new Chart(document.getElementById('chartTop'), {
        type: 'bar',
        data: {
            labels: @json($topProductos->pluck('nombre')),
            datasets: [{
                label:'Unidades', data:@json($topProductos->pluck('unidades')),
                backgroundColor:['#4f46e5','#6366f1','#818cf8','#a5b4fc','#c7d2fe'],
                borderRadius:8, borderSkipped:false, maxBarThickness:26
            }]
        },
        options: {
            indexAxis:'y', responsive:true, maintainAspectRatio:false,
            plugins:{ legend:{ display:false }, tooltip:{ callbacks:{ label:(c)=> c.parsed.x.toLocaleString('es-PE')+' und.' } } },
            scales:{
                x:{ beginAtZero:true, grid:{ color:grid }, ticks:{ color:axis }, border:{ display:false } },
                y:{ grid:{ display:false }, ticks:{ color:'#475569' }, border:{ display:false } }
            }
        }
    });
</script>
@endsection
