@extends('layouts.app')

@php $editing = $cliente->exists; @endphp
@section('title', $editing ? 'Editar cliente' : 'Nuevo cliente')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center gap-2 text-sm text-slate-400">
        <a href="{{ route('dashboard') }}" class="hover:text-slate-600">Inicio</a><span>/</span>
        <a href="{{ route('clientes.index') }}" class="hover:text-slate-600">Clientes</a><span>/</span>
        <span class="text-slate-600 font-medium">{{ $editing ? 'Editar' : 'Nuevo' }}</span>
    </div>

    <h1 class="text-2xl font-bold text-slate-800">{{ $editing ? 'Editar cliente' : 'Nuevo cliente' }}</h1>

    <form method="POST" action="{{ $editing ? route('clientes.update', $cliente) : route('clientes.store') }}"
          class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-100 p-6 sm:p-8 space-y-5">
        @csrf
        @if($editing) @method('PUT') @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div class="sm:col-span-2">
                <x-field name="nombre" label="Nombre / Razón social" :value="$cliente->nombre" required placeholder="Ej. Confecciones Andina S.A.C." />
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Tipo de documento <span class="text-rose-500">*</span></label>
                <select name="tipo_documento" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 focus:border-brand-500 focus:ring-2 focus:ring-brand-200 outline-none">
                    @foreach(['DNI','RUC','CE'] as $td)
                        <option value="{{ $td }}" @selected(old('tipo_documento', $cliente->tipo_documento)===$td)>{{ $td }}</option>
                    @endforeach
                </select>
            </div>
            <x-field name="numero_documento" label="Número de documento" :value="$cliente->numero_documento" placeholder="Ej. 20481234567" />

            <x-field name="email" label="Email" type="email" :value="$cliente->email" placeholder="cliente@correo.com" />
            <x-field name="telefono" label="Teléfono" :value="$cliente->telefono" placeholder="Ej. 987 654 321" />

            <x-field name="ciudad" label="Ciudad" :value="$cliente->ciudad" placeholder="Ej. Lima" />
            <x-field name="contacto" label="Persona de contacto" :value="$cliente->contacto" />

            <div class="sm:col-span-2">
                <x-field name="direccion" label="Dirección" :value="$cliente->direccion" />
            </div>
        </div>

        <label class="flex items-center gap-2.5 text-sm text-slate-600 select-none pt-2">
            <input type="checkbox" name="estado" value="1" @checked(old('estado', $editing ? $cliente->estado : true)) class="rounded border-slate-300 text-brand-600 focus:ring-brand-500">
            Cliente activo
        </label>

        <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
            <button type="submit" class="rounded-xl bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-brand-600/30 hover:bg-brand-700">{{ $editing ? 'Guardar cambios' : 'Registrar cliente' }}</button>
            <a href="{{ route('clientes.index') }}" class="rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50">Cancelar</a>
        </div>
    </form>
</div>
@endsection
