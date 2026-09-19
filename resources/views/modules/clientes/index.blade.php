@extends('layouts.app')

@section('title', 'Clientes')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="flex items-center gap-2 text-sm text-slate-400">
        <a href="{{ route('dashboard') }}" class="hover:text-slate-600">Inicio</a><span>/</span>
        <span class="text-slate-600 font-medium">Clientes</span>
    </div>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Clientes</h1>
            <p class="text-slate-500">Cartera de clientes del taller.</p>
        </div>
        <a href="{{ route('clientes.create') }}" class="rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-brand-600/30 hover:bg-brand-700">+ Nuevo cliente</a>
    </div>

    <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-100 overflow-hidden">
        <div class="p-4 border-b border-slate-100">
            <form method="GET" class="relative max-w-sm">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                </span>
                <input type="text" name="q" value="{{ $q }}" placeholder="Buscar por nombre, documento o email…"
                       class="w-full rounded-xl border border-slate-200 bg-slate-50 pl-10 pr-4 py-2.5 text-sm focus:bg-white focus:border-brand-400 focus:ring-2 focus:ring-brand-100 outline-none">
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="text-xs uppercase text-slate-400 bg-slate-50">
                    <tr>
                        <th class="text-left font-semibold px-6 py-3">Cliente</th>
                        <th class="text-left font-semibold px-6 py-3">Documento</th>
                        <th class="text-left font-semibold px-6 py-3">Contacto</th>
                        <th class="text-left font-semibold px-6 py-3">Ciudad</th>
                        <th class="text-center font-semibold px-6 py-3">Estado</th>
                        <th class="text-right font-semibold px-6 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($clientes as $c)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-3.5">
                                <p class="font-semibold text-slate-700">{{ $c->nombre }}</p>
                                <p class="text-xs text-slate-400">{{ $c->email ?: '—' }}</p>
                            </td>
                            <td class="px-6 py-3.5 text-slate-600">{{ $c->tipo_documento }} {{ $c->numero_documento }}</td>
                            <td class="px-6 py-3.5 text-slate-600">{{ $c->telefono ?: '—' }}</td>
                            <td class="px-6 py-3.5 text-slate-600">{{ $c->ciudad ?: '—' }}</td>
                            <td class="px-6 py-3.5 text-center">
                                @if($c->estado)
                                    <span class="text-xs font-semibold px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-600">Activo</span>
                                @else
                                    <span class="text-xs font-semibold px-2.5 py-1 rounded-lg bg-slate-100 text-slate-500">Inactivo</span>
                                @endif
                            </td>
                            <td class="px-6 py-3.5">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('clientes.edit', $c) }}" class="p-2 rounded-lg text-slate-400 hover:bg-brand-50 hover:text-brand-600" title="Editar">
                                        <svg class="h-4.5 w-4.5 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                    </a>
                                    <form method="POST" action="{{ route('clientes.destroy', $c) }}" onsubmit="return confirm('¿Eliminar este cliente?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg text-slate-400 hover:bg-rose-50 hover:text-rose-600" title="Eliminar">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-16 text-center text-slate-400">No hay clientes registrados. <a href="{{ route('clientes.create') }}" class="text-brand-600 font-medium">Crear el primero</a></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($clientes->hasPages())
            <div class="p-4 border-t border-slate-100">{{ $clientes->links() }}</div>
        @endif
    </div>
</div>
@endsection
