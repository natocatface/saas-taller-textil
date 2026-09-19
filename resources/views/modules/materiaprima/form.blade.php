@extends('layouts.app')

@php $editing = $materia->exists; @endphp
@section('title', $editing ? 'Editar insumo' : 'Nuevo insumo')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center gap-2 text-sm text-slate-400">
        <a href="{{ route('dashboard') }}" class="hover:text-slate-600">Inicio</a><span>/</span>
        <a href="{{ route('materiaprima.index') }}" class="hover:text-slate-600">Materia Prima</a><span>/</span>
        <span class="text-slate-600 font-medium">{{ $editing ? 'Editar' : 'Nuevo' }}</span>
    </div>

    <h1 class="text-2xl font-bold text-slate-800">{{ $editing ? 'Editar insumo' : 'Nuevo insumo' }}</h1>

    <form method="POST" action="{{ $editing ? route('materiaprima.update', $materia) : route('materiaprima.store') }}"
          class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-100 p-6 sm:p-8 space-y-5">
        @csrf
        @if($editing) @method('PUT') @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <x-field name="codigo" label="Código" :value="$materia->codigo" required placeholder="Ej. TEL-001" />
            <x-field name="nombre" label="Nombre" :value="$materia->nombre" required placeholder="Ej. Tela algodón jersey" />

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Tipo <span class="text-rose-500">*</span></label>
                <select name="tipo" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 focus:border-brand-500 focus:ring-2 focus:ring-brand-200 outline-none">
                    @foreach(['tela'=>'Tela','hilo'=>'Hilo','avio'=>'Avío','otro'=>'Otro'] as $val=>$lbl)
                        <option value="{{ $val }}" @selected(old('tipo', $materia->tipo)===$val)>{{ $lbl }}</option>
                    @endforeach
                </select>
            </div>
            <x-field name="unidad" label="Unidad de medida" :value="$materia->unidad ?: 'metro'" required placeholder="metro, kilo, cono, unidad" />

            <x-field name="stock" label="Stock actual" type="number" step="0.01" :value="$materia->stock ?? '0'" required />
            <x-field name="stock_minimo" label="Stock mínimo" type="number" step="0.01" :value="$materia->stock_minimo ?? '0'" required />

            <x-field name="costo_unitario" label="Costo unitario (S/)" type="number" step="0.01" :value="$materia->costo_unitario ?? '0.00'" required />
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Proveedor</label>
                <select name="proveedor_id" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 focus:border-brand-500 focus:ring-2 focus:ring-brand-200 outline-none">
                    <option value="">— Sin proveedor —</option>
                    @foreach($proveedores as $prov)
                        <option value="{{ $prov->id }}" @selected(old('proveedor_id', $materia->proveedor_id)==$prov->id)>{{ $prov->razon_social }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <label class="flex items-center gap-2.5 text-sm text-slate-600 select-none pt-2">
            <input type="checkbox" name="estado" value="1" @checked(old('estado', $editing ? $materia->estado : true)) class="rounded border-slate-300 text-brand-600 focus:ring-brand-500">
            Insumo activo
        </label>

        <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
            <button type="submit" class="rounded-xl bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-brand-600/30 hover:bg-brand-700">{{ $editing ? 'Guardar cambios' : 'Registrar insumo' }}</button>
            <a href="{{ route('materiaprima.index') }}" class="rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50">Cancelar</a>
        </div>
    </form>
</div>
@endsection
