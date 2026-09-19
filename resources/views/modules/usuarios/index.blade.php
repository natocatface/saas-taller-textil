@extends('layouts.app')

@section('title', 'Usuarios')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="flex items-center gap-2 text-sm text-slate-400">
        <a href="{{ route('dashboard') }}" class="hover:text-slate-600">Inicio</a><span>/</span>
        <span class="text-slate-600 font-medium">Usuarios</span>
    </div>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Usuarios y Roles</h1>
            <p class="text-slate-500">Usuarios con acceso al sistema.</p>
        </div>
        <a href="{{ route('usuarios.create') }}" class="rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-brand-600/30 hover:bg-brand-700">+ Nuevo usuario</a>
    </div>

    <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-100 overflow-hidden">
        <div class="p-4 border-b border-slate-100">
            <form method="GET" class="relative max-w-sm">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                </span>
                <input type="text" name="q" value="{{ $q }}" placeholder="Buscar por nombre o email…"
                       class="w-full rounded-xl border border-slate-200 bg-slate-50 pl-10 pr-4 py-2.5 text-sm focus:bg-white focus:border-brand-400 focus:ring-2 focus:ring-brand-100 outline-none">
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="text-xs uppercase text-slate-400 bg-slate-50">
                    <tr>
                        <th class="text-left font-semibold px-6 py-3">Usuario</th>
                        <th class="text-left font-semibold px-6 py-3">Rol</th>
                        <th class="text-left font-semibold px-6 py-3">Cargo</th>
                        <th class="text-center font-semibold px-6 py-3">Estado</th>
                        <th class="text-right font-semibold px-6 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @php $roleColors = ['admin'=>'bg-purple-50 text-purple-600','supervisor'=>'bg-brand-50 text-brand-600','operario'=>'bg-slate-100 text-slate-600','ventas'=>'bg-amber-50 text-amber-600']; @endphp
                    @forelse ($usuarios as $u)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-3.5">
                                <div class="flex items-center gap-3">
                                    <span class="h-9 w-9 rounded-full bg-gradient-to-br from-brand-500 to-purple-600 text-white text-xs font-semibold flex items-center justify-center">{{ $u->initials() }}</span>
                                    <div>
                                        <p class="font-semibold text-slate-700">{{ $u->name }} @if($u->id===auth()->id())<span class="text-xs text-slate-400">(tú)</span>@endif</p>
                                        <p class="text-xs text-slate-400">{{ $u->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-3.5"><span class="text-xs font-semibold px-2.5 py-1 rounded-lg {{ $roleColors[$u->role] ?? 'bg-slate-100 text-slate-600' }} capitalize">{{ $u->role }}</span></td>
                            <td class="px-6 py-3.5 text-slate-600">{{ $u->position ?: '—' }}</td>
                            <td class="px-6 py-3.5 text-center">
                                <span class="text-xs font-semibold px-2.5 py-1 rounded-lg {{ $u->is_active ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-500' }}">{{ $u->is_active ? 'Activo' : 'Inactivo' }}</span>
                            </td>
                            <td class="px-6 py-3.5">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('usuarios.edit', $u) }}" class="p-2 rounded-lg text-slate-400 hover:bg-brand-50 hover:text-brand-600" title="Editar">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                    </a>
                                    @if($u->id!==auth()->id())
                                    <form method="POST" action="{{ route('usuarios.destroy', $u) }}" onsubmit="return confirm('¿Eliminar este usuario?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg text-slate-400 hover:bg-rose-50 hover:text-rose-600" title="Eliminar">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166M18.16 5.79L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165"/></svg>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-16 text-center text-slate-400">No hay usuarios.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($usuarios->hasPages())
            <div class="p-4 border-t border-slate-100">{{ $usuarios->links() }}</div>
        @endif
    </div>
</div>
@endsection
