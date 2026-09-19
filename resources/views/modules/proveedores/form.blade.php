@extends('layouts.app')

@php $editing = $proveedor->exists; @endphp
@section('title', $editing ? 'Editar proveedor' : 'Nuevo proveedor')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center gap-2 text-sm text-slate-400">
        <a href="{{ route('dashboard') }}" class="hover:text-slate-600">Inicio</a><span>/</span>
        <a href="{{ route('proveedores.index') }}" class="hover:text-slate-600">Proveedores</a><span>/</span>
        <span class="text-slate-600 font-medium">{{ $editing ? 'Editar' : 'Nuevo' }}</span>
    </div>

    <h1 class="text-2xl font-bold text-slate-800">{{ $editing ? 'Editar proveedor' : 'Nuevo proveedor' }}</h1>

    <form method="POST" action="{{ $editing ? route('proveedores.update', $proveedor) : route('proveedores.store') }}"
          class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-100 p-6 sm:p-8 space-y-5">
        @csrf
        @if($editing) @method('PUT') @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div class="sm:col-span-2">
                <x-field name="razon_social" label="Razón social" :value="$proveedor->razon_social" required placeholder="Ej. Textiles Import S.A.C." />
            </div>
            <x-field name="ruc" label="RUC" :value="$proveedor->ruc" placeholder="Ej. 20481234567" />
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Tipo <span class="text-rose-500">*</span></label>
                <select name="tipo" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 focus:border-brand-500 focus:ring-2 focus:ring-brand-200 outline-none">
                    @foreach(['nacional'=>'Nacional','importado'=>'Importado'] as $val=>$lbl)
                        <option value="{{ $val }}" @selected(old('tipo', $proveedor->tipo)===$val)>{{ $lbl }}</option>
                    @endforeach
                </select>
            </div>
            <x-field name="email" label="Email" type="email" :value="$proveedor->email" />
            <x-field name="telefono" label="Teléfono" :value="$proveedor->telefono" />
            <x-field name="contacto" label="Persona de contacto" :value="$proveedor->contacto" />
            <x-field name="condicion_pago" label="Condición de pago" :value="$proveedor->condicion_pago" placeholder="Ej. Crédito 30 días" />
            <div class="sm:col-span-2">
                <x-field name="direccion" label="Dirección" :value="$proveedor->direccion" />
            </div>
        </div>

        <label class="flex items-center gap-2.5 text-sm text-slate-600 select-none pt-2">
            <input type="checkbox" name="estado" value="1" @checked(old('estado', $editing ? $proveedor->estado : true)) class="rounded border-slate-300 text-brand-600 focus:ring-brand-500">
            Proveedor activo
        </label>

        <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
            <button type="submit" class="rounded-xl bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-brand-600/30 hover:bg-brand-700">{{ $editing ? 'Guardar cambios' : 'Registrar proveedor' }}</button>
            <a href="{{ route('proveedores.index') }}" class="rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50">Cancelar</a>
        </div>
    </form>
</div>
@endsection
