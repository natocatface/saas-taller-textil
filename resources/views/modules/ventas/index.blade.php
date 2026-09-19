@extends('layouts.app')

@section('title', 'Ventas')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="flex items-center gap-2 text-sm text-slate-400">
        <a href="{{ route('dashboard') }}" class="hover:text-slate-600">Inicio</a><span>/</span>
        <span class="text-slate-600 font-medium">Ventas</span>
    </div>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Ventas</h1>
            <p class="text-slate-500">Comprobantes y facturación.</p>
        </div>
        <a href="{{ route('ventas.create') }}" class="rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-brand-600/30 hover:bg-brand-700">+ Nueva venta</a>
    </div>

    <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-100 overflow-hidden">
        <div class="p-4 border-b border-slate-100">
            <form method="GET" class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1 max-w-sm">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                    </span>
                    <input type="text" name="q" value="{{ $q }}" placeholder="Buscar por código o cliente…"
                           class="w-full rounded-xl border border-slate-200 bg-slate-50 pl-10 pr-4 py-2.5 text-sm focus:bg-white focus:border-brand-400 focus:ring-2 focus:ring-brand-100 outline-none">
                </div>
                <select name="estado" onchange="this.form.submit()" class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm focus:bg-white focus:border-brand-400 outline-none">
                    <option value="">Todos los estados</option>
                    @foreach(\App\Models\Venta::ESTADOS as $val=>$lbl)
                        <option value="{{ $val }}" @selected($estado===$val)>{{ $lbl }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="text-xs uppercase text-slate-400 bg-slate-50">
                    <tr>
                        <th class="text-left font-semibold px-6 py-3">Comprobante</th>
                        <th class="text-left font-semibold px-6 py-3">Cliente</th>
                        <th class="text-left font-semibold px-6 py-3">Tipo</th>
                        <th class="text-left font-semibold px-6 py-3">Estado</th>
                        <th class="text-right font-semibold px-6 py-3">Total</th>
                        <th class="text-right font-semibold px-6 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($ventas as $v)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-3.5">
                                <a href="{{ route('ventas.show', $v) }}" class="font-semibold text-brand-600 hover:text-brand-700">{{ $v->codigo }}</a>
                                <p class="text-xs text-slate-400">{{ $v->fecha?->format('d/m/Y') }}</p>
                            </td>
                            <td class="px-6 py-3.5 text-slate-600">{{ $v->cliente->nombre ?? '—' }}</td>
                            <td class="px-6 py-3.5"><span class="text-xs font-semibold px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600 capitalize">{{ $v->tipo_comprobante }}</span></td>
                            <td class="px-6 py-3.5"><span class="text-xs font-semibold px-2.5 py-1 rounded-lg {{ $v->estadoColor() }}">{{ \App\Models\Venta::ESTADOS[$v->estado] }}</span></td>
                            <td class="px-6 py-3.5 text-right font-semibold text-slate-700">S/ {{ number_format($v->total, 2) }}</td>
                            <td class="px-6 py-3.5">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('ventas.show', $v) }}" class="p-2 rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-600" title="Ver">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </a>
                                    <a href="{{ route('ventas.edit', $v) }}" class="p-2 rounded-lg text-slate-400 hover:bg-brand-50 hover:text-brand-600" title="Editar">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                    </a>
                                    <form method="POST" action="{{ route('ventas.destroy', $v) }}" onsubmit="return confirm('¿Eliminar esta venta?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg text-slate-400 hover:bg-rose-50 hover:text-rose-600" title="Eliminar">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166M18.16 5.79L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-16 text-center text-slate-400">No hay ventas registradas. <a href="{{ route('ventas.create') }}" class="text-brand-600 font-medium">Registrar la primera</a></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($ventas->hasPages())
            <div class="p-4 border-t border-slate-100">{{ $ventas->links() }}</div>
        @endif
    </div>
</div>
@endsection
