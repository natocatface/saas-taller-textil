@extends('layouts.app')

@php $editing = $producto->exists; @endphp
@section('title', $editing ? 'Editar producto' : 'Nuevo producto')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center gap-2 text-sm text-slate-400">
        <a href="{{ route('dashboard') }}" class="hover:text-slate-600">Inicio</a><span>/</span>
        <a href="{{ route('productos.index') }}" class="hover:text-slate-600">Productos</a><span>/</span>
        <span class="text-slate-600 font-medium">{{ $editing ? 'Editar' : 'Nuevo' }}</span>
    </div>

    <h1 class="text-2xl font-bold text-slate-800">{{ $editing ? 'Editar producto' : 'Nuevo producto' }}</h1>

    <form method="POST" action="{{ $editing ? route('productos.update', $producto) : route('productos.store') }}"
          class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-100 p-6 sm:p-8 space-y-5">
        @csrf
        @if($editing) @method('PUT') @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <x-field name="codigo" label="Código" :value="$producto->codigo" required placeholder="Ej. POL-001" />
            <x-field name="nombre" label="Nombre" :value="$producto->nombre" required placeholder="Ej. Polo cuello redondo" />
            <x-field name="categoria" label="Categoría" :value="$producto->categoria" placeholder="Ej. Polos" />
            <x-field name="talla" label="Talla" :value="$producto->talla" placeholder="Ej. M / 38" />
            <x-field name="color" label="Color" :value="$producto->color" placeholder="Ej. Azul marino" />
            <x-field name="stock" label="Stock" type="number" :value="$producto->stock ?? 0" required />
            <x-field name="precio" label="Precio de venta (S/)" type="number" step="0.01" :value="$producto->precio ?? '0.00'" required />
            <x-field name="costo" label="Costo (S/)" type="number" step="0.01" :value="$producto->costo ?? '0.00'" required />
        </div>

        <label class="flex items-center gap-2.5 text-sm text-slate-600 select-none pt-2">
            <input type="checkbox" name="estado" value="1" @checked(old('estado', $editing ? $producto->estado : true)) class="rounded border-slate-300 text-brand-600 focus:ring-brand-500">
            Producto activo
        </label>

        <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
            <button type="submit" class="rounded-xl bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-brand-600/30 hover:bg-brand-700">{{ $editing ? 'Guardar cambios' : 'Registrar producto' }}</button>
            <a href="{{ route('productos.index') }}" class="rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50">Cancelar</a>
        </div>
    </form>
</div>
@endsection
