@extends('layouts.app')

@section('title', 'Materia Prima')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="flex items-center gap-2 text-sm text-slate-400">
        <a href="{{ route('dashboard') }}" class="hover:text-slate-600">Inicio</a><span>/</span>
        <span class="text-slate-600 font-medium">Materia Prima</span>
    </div>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Materia Prima</h1>
            <p class="text-slate-500">Telas, hilos y avíos del taller.</p>
        </div>
        <a href="{{ route('materiaprima.create') }}" class="rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-brand-600/30 hover:bg-brand-700">+ Nuevo insumo</a>
    </div>

    <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-100 overflow-hidden">
        <div class="p-4 border-b border-slate-100">
            <form method="GET" class="relative max-w-sm">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                </span>
                <input type="text" name="q" value="{{ $q }}" placeholder="Buscar por nombre o código…"
                       class="w-full rounded-xl border border-slate-200 bg-slate-50 pl-10 pr-4 py-2.5 text-sm focus:bg-white focus:border-brand-400 focus:ring-2 focus:ring-brand-100 outline-none">
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="text-xs uppercase text-slate-400 bg-slate-50">
                    <tr>
                        <th class="text-left font-semibold px-6 py-3">Código</th>
                        <th class="text-left font-semibold px-6 py-3">Insumo</th>
                        <th class="text-left font-semibold px-6 py-3">Tipo</th>
                        <th class="text-left font-semibold px-6 py-3">Proveedor</th>
                        <th class="text-center font-semibold px-6 py-3">Stock</th>
                        <th class="text-right font-semibold px-6 py-3">Costo unit.</th>
                        <th class="text-right font-semibold px-6 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($materias as $m)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-3.5 font-mono text-xs text-slate-500">{{ $m->codigo }}</td>
                            <td class="px-6 py-3.5 font-semibold text-slate-700">{{ $m->nombre }}</td>
                            <td class="px-6 py-3.5"><span class="text-xs font-semibold px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600 capitalize">{{ $m->tipo }}</span></td>
                            <td class="px-6 py-3.5 text-slate-600">{{ $m->proveedor->razon_social ?? '—' }}</td>
                            <td class="px-6 py-3.5 text-center">
                                <span class="text-xs font-semibold px-2.5 py-1 rounded-lg {{ $m->stock_bajo ? 'bg-rose-50 text-rose-600' : 'bg-emerald-50 text-emerald-600' }}">
                                    {{ rtrim(rtrim(number_format($m->stock, 2), '0'), '.') }} {{ $m->unidad }}
                                </span>
                                @if($m->stock_bajo)<p class="text-[10px] text-rose-500 mt-1">Stock bajo</p>@endif
                            </td>
                            <td class="px-6 py-3.5 text-right text-slate-700">S/ {{ number_format($m->costo_unitario, 2) }}</td>
                            <td class="px-6 py-3.5">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('materiaprima.edit', $m) }}" class="p-2 rounded-lg text-slate-400 hover:bg-brand-50 hover:text-brand-600" title="Editar">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                    </a>
                                    <form method="POST" action="{{ route('materiaprima.destroy', $m) }}" onsubmit="return confirm('¿Eliminar este insumo?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg text-slate-400 hover:bg-rose-50 hover:text-rose-600" title="Eliminar">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166M18.16 5.79L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-6 py-16 text-center text-slate-400">No hay insumos registrados. <a href="{{ route('materiaprima.create') }}" class="text-brand-600 font-medium">Crear el primero</a></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($materias->hasPages())
            <div class="p-4 border-t border-slate-100">{{ $materias->links() }}</div>
        @endif
    </div>
</div>
@endsection
