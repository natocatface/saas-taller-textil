@extends('layouts.app')

@section('title', 'Almacén')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="flex items-center gap-2 text-sm text-slate-400">
        <a href="{{ route('dashboard') }}" class="hover:text-slate-600">Inicio</a><span>/</span>
        <span class="text-slate-600 font-medium">Almacén</span>
    </div>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Almacén · Kardex</h1>
            <p class="text-slate-500">Movimientos de entrada, salida y ajuste de insumos.</p>
        </div>
        <a href="{{ route('almacen.create') }}" class="rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-brand-600/30 hover:bg-brand-700">+ Nuevo movimiento</a>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-100"><p class="text-sm text-slate-500">Entradas (mes)</p><p class="mt-1 text-2xl font-bold text-emerald-600">{{ $resumen['entradas'] }}</p></div>
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-100"><p class="text-sm text-slate-500">Salidas (mes)</p><p class="mt-1 text-2xl font-bold text-rose-600">{{ $resumen['salidas'] }}</p></div>
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-100"><p class="text-sm text-slate-500">Ajustes (mes)</p><p class="mt-1 text-2xl font-bold text-amber-600">{{ $resumen['ajustes'] }}</p></div>
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-100"><p class="text-sm text-slate-500">Insumos stock bajo</p><p class="mt-1 text-2xl font-bold text-slate-800">{{ $resumen['stock_bajo'] }}</p></div>
    </div>

    <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-100 overflow-hidden">
        <div class="p-4 border-b border-slate-100">
            <form method="GET" class="flex flex-col sm:flex-row gap-3">
                <select name="materia" onchange="this.form.submit()" class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm focus:bg-white focus:border-brand-400 outline-none">
                    <option value="">Todos los insumos</option>
                    @foreach($materias as $m)
                        <option value="{{ $m->id }}" @selected((string)$materiaId===(string)$m->id)>{{ $m->nombre }}</option>
                    @endforeach
                </select>
                <select name="tipo" onchange="this.form.submit()" class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm focus:bg-white focus:border-brand-400 outline-none">
                    <option value="">Todos los tipos</option>
                    @foreach(\App\Models\MovimientoInventario::TIPOS as $val=>$lbl)
                        <option value="{{ $val }}" @selected($tipo===$val)>{{ $lbl }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="text-xs uppercase text-slate-400 bg-slate-50">
                    <tr>
                        <th class="text-left font-semibold px-6 py-3">Fecha</th>
                        <th class="text-left font-semibold px-6 py-3">Insumo</th>
                        <th class="text-left font-semibold px-6 py-3">Tipo</th>
                        <th class="text-right font-semibold px-4 py-3">Cantidad</th>
                        <th class="text-right font-semibold px-4 py-3">Stock resultante</th>
                        <th class="text-left font-semibold px-6 py-3">Motivo</th>
                        <th class="text-right font-semibold px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($movimientos as $mov)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-3.5 text-slate-600">{{ $mov->fecha?->format('d/m/Y') }}</td>
                            <td class="px-6 py-3.5 font-medium text-slate-700">{{ $mov->materiaPrima->nombre ?? '—' }}</td>
                            <td class="px-6 py-3.5"><span class="text-xs font-semibold px-2.5 py-1 rounded-lg {{ $mov->tipoColor() }}">{{ \App\Models\MovimientoInventario::TIPOS[$mov->tipo] }}</span></td>
                            <td class="px-4 py-3.5 text-right font-medium {{ $mov->tipo==='salida' ? 'text-rose-600' : 'text-emerald-600' }}">
                                {{ $mov->tipo==='salida' ? '-' : ($mov->tipo==='entrada' ? '+' : '') }}{{ rtrim(rtrim(number_format($mov->cantidad,2),'0'),'.') }}
                            </td>
                            <td class="px-4 py-3.5 text-right text-slate-700">{{ rtrim(rtrim(number_format($mov->stock_resultante,2),'0'),'.') }}</td>
                            <td class="px-6 py-3.5 text-slate-500">{{ $mov->motivo ?: '—' }}</td>
                            <td class="px-6 py-3.5 text-right">
                                <form method="POST" action="{{ route('almacen.destroy', $mov) }}" onsubmit="return confirm('¿Revertir y eliminar este movimiento? El stock se ajustará.')" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 rounded-lg text-slate-400 hover:bg-rose-50 hover:text-rose-600" title="Revertir">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3"/></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-6 py-16 text-center text-slate-400">No hay movimientos. <a href="{{ route('almacen.create') }}" class="text-brand-600 font-medium">Registrar el primero</a></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($movimientos->hasPages())
            <div class="p-4 border-t border-slate-100">{{ $movimientos->links() }}</div>
        @endif
    </div>
</div>
@endsection
