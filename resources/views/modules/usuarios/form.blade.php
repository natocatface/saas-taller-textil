@extends('layouts.app')

@php $editing = $usuario->exists; @endphp
@section('title', $editing ? 'Editar usuario' : 'Nuevo usuario')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center gap-2 text-sm text-slate-400">
        <a href="{{ route('dashboard') }}" class="hover:text-slate-600">Inicio</a><span>/</span>
        <a href="{{ route('usuarios.index') }}" class="hover:text-slate-600">Usuarios</a><span>/</span>
        <span class="text-slate-600 font-medium">{{ $editing ? 'Editar' : 'Nuevo' }}</span>
    </div>

    <h1 class="text-2xl font-bold text-slate-800">{{ $editing ? 'Editar usuario' : 'Nuevo usuario' }}</h1>

    <form method="POST" action="{{ $editing ? route('usuarios.update', $usuario) : route('usuarios.store') }}"
          class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-100 p-6 sm:p-8 space-y-5">
        @csrf
        @if($editing) @method('PUT') @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <x-field name="name" label="Nombre" :value="$usuario->name" required />
            <x-field name="email" label="Email" type="email" :value="$usuario->email" required />

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Rol <span class="text-rose-500">*</span></label>
                <select name="role" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 focus:border-brand-500 focus:ring-2 focus:ring-brand-200 outline-none">
                    @foreach($roles as $r)
                        <option value="{{ $r }}" @selected(old('role', $usuario->role ?: 'operario')===$r)>{{ ucfirst($r) }}</option>
                    @endforeach
                </select>
            </div>
            <x-field name="position" label="Cargo" :value="$usuario->position" placeholder="Ej. Jefe de Producción" />

            <x-field name="phone" label="Teléfono" :value="$usuario->phone" />
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Contraseña @if(!$editing)<span class="text-rose-500">*</span>@endif</label>
                <input type="password" name="password" autocomplete="new-password"
                       placeholder="{{ $editing ? 'Dejar en blanco para no cambiar' : 'Mínimo 6 caracteres' }}"
                       class="w-full rounded-xl border {{ $errors->has('password') ? 'border-rose-400' : 'border-slate-300' }} bg-white px-4 py-2.5 focus:border-brand-500 focus:ring-2 focus:ring-brand-200 outline-none">
                @error('password')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
            </div>
        </div>

        <label class="flex items-center gap-2.5 text-sm text-slate-600 select-none pt-2">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $editing ? $usuario->is_active : true)) class="rounded border-slate-300 text-brand-600 focus:ring-brand-500">
            Usuario activo (puede iniciar sesión)
        </label>

        <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
            <button type="submit" class="rounded-xl bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-brand-600/30 hover:bg-brand-700">{{ $editing ? 'Guardar cambios' : 'Crear usuario' }}</button>
            <a href="{{ route('usuarios.index') }}" class="rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50">Cancelar</a>
        </div>
    </form>
</div>
@endsection
