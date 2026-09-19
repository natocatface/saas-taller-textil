@extends('layouts.app')

@php $editing = $empleado->exists; @endphp
@section('title', $editing ? 'Editar empleado' : 'Nuevo empleado')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center gap-2 text-sm text-slate-400">
        <a href="{{ route('dashboard') }}" class="hover:text-slate-600">Inicio</a><span>/</span>
        <a href="{{ route('empleados.index') }}" class="hover:text-slate-600">Empleados</a><span>/</span>
        <span class="text-slate-600 font-medium">{{ $editing ? 'Editar' : 'Nuevo' }}</span>
    </div>

    <h1 class="text-2xl font-bold text-slate-800">{{ $editing ? 'Editar empleado' : 'Nuevo empleado' }}</h1>

    <form method="POST" action="{{ $editing ? route('empleados.update', $empleado) : route('empleados.store') }}"
          class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-100 p-6 sm:p-8 space-y-5">
        @csrf
        @if($editing) @method('PUT') @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div class="sm:col-span-2">
                <x-field name="nombre" label="Nombre completo" :value="$empleado->nombre" required placeholder="Ej. María Torres Quispe" />
            </div>
            <x-field name="dni" label="DNI" :value="$empleado->dni" placeholder="Ej. 45678912" />
            <x-field name="cargo" label="Cargo" :value="$empleado->cargo" placeholder="Ej. Costurera" />

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Área <span class="text-rose-500">*</span></label>
                <select name="area" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 focus:border-brand-500 focus:ring-2 focus:ring-brand-200 outline-none">
                    @foreach($areas as $a)
                        <option value="{{ $a }}" @selected(old('area', $empleado->area)===$a)>{{ $a }}</option>
                    @endforeach
                </select>
            </div>
            <x-field name="fecha_ingreso" label="Fecha de ingreso" type="date" :value="optional($empleado->fecha_ingreso)->format('Y-m-d')" />

            <x-field name="telefono" label="Teléfono" :value="$empleado->telefono" />
            <x-field name="email" label="Email" type="email" :value="$empleado->email" />

            <x-field name="salario" label="Salario (S/)" type="number" step="0.01" :value="$empleado->salario ?? '0.00'" required />
        </div>

        <label class="flex items-center gap-2.5 text-sm text-slate-600 select-none pt-2">
            <input type="checkbox" name="estado" value="1" @checked(old('estado', $editing ? $empleado->estado : true)) class="rounded border-slate-300 text-brand-600 focus:ring-brand-500">
            Empleado activo
        </label>

        <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
            <button type="submit" class="rounded-xl bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-brand-600/30 hover:bg-brand-700">{{ $editing ? 'Guardar cambios' : 'Registrar empleado' }}</button>
            <a href="{{ route('empleados.index') }}" class="rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50">Cancelar</a>
        </div>
    </form>
</div>
@endsection
