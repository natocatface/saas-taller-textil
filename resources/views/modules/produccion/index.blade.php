@extends('layouts.app')

@section('title', 'Producción')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="flex items-center gap-2 text-sm text-slate-400">
        <a href="{{ route('dashboard') }}" class="hover:text-slate-600">Inicio</a><span>/</span>
        <span class="text-slate-600 font-medium">Producción</span>
    </div>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Producción</h1>
            <p class="text-slate-500">Órdenes de producción por etapa.</p>
        </div>
        <a href="{{ route('produccion.create') }}" class="rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-brand-600/30 hover:bg-brand-700">+ Nueva orden</a>
    </div>

    <!-- Tablero por etapa -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        @php $colores = ['corte'=>'from-brand-500 to-indigo-600','confeccion'=>'from-emerald-500 to-teal-600','acabado'=>'from-amber-500 to-orange-600','control'=>'from-purple-500 to-fuchsia-600','terminado'=>'from-slate-400 to-slate-500']; @endphp
        @foreach(\App\Models\OrdenProduccion::ETAPAS as $val=>$lbl)
            <a href="{{ route('produccion.index', ['etapa'=>$val]) }}" class="rounded-2xl bg-white p-4 shadow-sm ring-1 {{ ($etapa ?? '')===$val ? 'ring-brand-400' : 'ring-slate-100' }} hover:shadow-md transition">
                <div class="h-9 w-9 rounded-lg bg-gradient-to-br {{ $colores[$val] }} mb-3"></div>
                <p class="text-2xl font-bold text-slate-800">{{ $resumen[$val] ?? 0 }}</p>
                <p class="text-xs text-slate-500">{{ $lbl }}</p>
            </a>
        @endforeach
    </div>

    <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-100 overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex flex-col sm:flex-row gap-3">
            <form method="GET" class="relative flex-1 max-w-sm">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                </span>
                <input type="text" name="q" value="{{ $q }}" placeholder="Buscar por código o producto…"
                       class="w-full rounded-xl border border-slate-200 bg-slate-50 pl-10 pr-4 py-2.5 text-sm focus:bg-white focus:border-brand-400 focus:ring-2 focus:ring-brand-100 outline-none">
                @if($etapa)<input type="hidden" name="etapa" value="{{ $etapa }}">@endif
            </form>
            @if($etapa)<a href="{{ route('produccion.index') }}" class="self-start rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50">Ver todas</a>@endif
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="text-xs uppercase text-slate-400 bg-slate-50">
                    <tr>
                        <th class="text-left font-semibold px-6 py-3">Orden</th>
                        <th class="text-left font-semibold px-6 py-3">Producto</th>
                        <th class="text-center font-semibold px-4 py-3">Cant.</th>
                        <th class="text-left font-semibold px-6 py-3">Operario</th>
                        <th class="text-left font-semibold px-6 py-3">Etapa</th>
                        <th class="text-left font-semibold px-6 py-3 w-40">Avance</th>
                        <th class="text-right font-semibold px-6 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($ordenes as $o)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-3.5">
                                <p class="font-semibold text-slate-700">{{ $o->codigo }}</p>
                                <p class="text-xs text-slate-400">{{ $o->pedido->codigo ?? 'Sin pedido' }}</p>
                            </td>
                            <td class="px-6 py-3.5 text-slate-600">{{ $o->producto->nombre ?? '—' }}</td>
                            <td class="px-4 py-3.5 text-center text-slate-600">{{ $o->cantidad }}</td>
                            <td class="px-6 py-3.5 text-slate-600">{{ $o->empleado->nombre ?? '—' }}</td>
                            <td class="px-6 py-3.5"><span class="text-xs font-semibold px-2.5 py-1 rounded-lg {{ $o->etapaColor() }}">{{ $o->etapaLabel() }}</span></td>
                            <td class="px-6 py-3.5">
                                <div class="flex items-center gap-2">
                                    <div class="h-2 flex-1 rounded-full bg-slate-100 overflow-hidden"><div class="h-full rounded-full {{ $o->estado==='terminado' ? 'bg-emerald-500' : 'bg-brand-500' }}" style="width: {{ $o->avance }}%"></div></div>
                                    <span class="text-xs text-slate-500 w-9 text-right">{{ $o->avance }}%</span>
                                </div>
                            </td>
                            <td class="px-6 py-3.5">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('produccion.edit', $o) }}" class="p-2 rounded-lg text-slate-400 hover:bg-brand-50 hover:text-brand-600" title="Editar">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                    </a>
                                    <form method="POST" action="{{ route('produccion.destroy', $o) }}" onsubmit="return confirm('¿Eliminar esta orden?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg text-slate-400 hover:bg-rose-50 hover:text-rose-600" title="Eliminar">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166M18.16 5.79L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-6 py-16 text-center text-slate-400">No hay órdenes de producción. <a href="{{ route('produccion.create') }}" class="text-brand-600 font-medium">Crear la primera</a></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($ordenes->hasPages())
            <div class="p-4 border-t border-slate-100">{{ $ordenes->links() }}</div>
        @endif
    </div>
</div>
@endsection
