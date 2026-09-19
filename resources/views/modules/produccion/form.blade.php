@extends('layouts.app')

@php $editing = $orden->exists; @endphp
@section('title', $editing ? 'Editar orden' : 'Nueva orden de producción')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center gap-2 text-sm text-slate-400">
        <a href="{{ route('dashboard') }}" class="hover:text-slate-600">Inicio</a><span>/</span>
        <a href="{{ route('produccion.index') }}" class="hover:text-slate-600">Producción</a><span>/</span>
        <span class="text-slate-600 font-medium">{{ $editing ? $orden->codigo : 'Nueva' }}</span>
    </div>

    <h1 class="text-2xl font-bold text-slate-800">{{ $editing ? 'Editar orden '.$orden->codigo : 'Nueva orden de producción' }}</h1>

    <form method="POST" action="{{ $editing ? route('produccion.update', $orden) : route('produccion.store') }}"
          class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-100 p-6 sm:p-8 space-y-5">
        @csrf
        @if($editing) @method('PUT') @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Producto <span class="text-rose-500">*</span></label>
                <select name="producto_id" required class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 focus:border-brand-500 focus:ring-2 focus:ring-brand-200 outline-none">
                    <option value="">— Seleccione —</option>
                    @foreach($productos as $p)
                        <option value="{{ $p->id }}" @selected(old('producto_id', $orden->producto_id)==$p->id)>{{ $p->nombre }} — {{ $p->codigo }}</option>
                    @endforeach
                </select>
            </div>
            <x-field name="cantidad" label="Cantidad a producir" type="number" :value="$orden->cantidad ?? 1" required />

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Pedido vinculado</label>
                <select name="pedido_id" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 focus:border-brand-500 focus:ring-2 focus:ring-brand-200 outline-none">
                    <option value="">— Sin pedido —</option>
                    @foreach($pedidos as $ped)
                        <option value="{{ $ped->id }}" @selected(old('pedido_id', $orden->pedido_id)==$ped->id)>{{ $ped->codigo }} — {{ $ped->cliente->nombre ?? '' }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Operario asignado</label>
                <select name="empleado_id" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 focus:border-brand-500 focus:ring-2 focus:ring-brand-200 outline-none">
                    <option value="">— Sin asignar —</option>
                    @foreach($empleados as $e)
                        <option value="{{ $e->id }}" @selected(old('empleado_id', $orden->empleado_id)==$e->id)>{{ $e->nombre }} ({{ $e->area }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Etapa <span class="text-rose-500">*</span></label>
                <select name="etapa" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 focus:border-brand-500 focus:ring-2 focus:ring-brand-200 outline-none">
                    @foreach(\App\Models\OrdenProduccion::ETAPAS as $val=>$lbl)
                        <option value="{{ $val }}" @selected(old('etapa', $orden->etapa ?: 'corte')===$val)>{{ $lbl }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Estado <span class="text-rose-500">*</span></label>
                <select name="estado" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 focus:border-brand-500 focus:ring-2 focus:ring-brand-200 outline-none">
                    @foreach(\App\Models\OrdenProduccion::ESTADOS as $val=>$lbl)
                        <option value="{{ $val }}" @selected(old('estado', $orden->estado ?: 'en_proceso')===$val)>{{ $lbl }}</option>
                    @endforeach
                </select>
            </div>

            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Avance: <span id="avanceLabel" class="font-semibold text-brand-600">{{ old('avance', $orden->avance ?? 0) }}%</span></label>
                <input type="range" name="avance" min="0" max="100" step="5" value="{{ old('avance', $orden->avance ?? 0) }}"
                       oninput="document.getElementById('avanceLabel').textContent = this.value + '%'"
                       class="w-full accent-indigo-600">
            </div>

            <x-field name="fecha_inicio" label="Fecha de inicio" type="date" :value="optional($orden->fecha_inicio)->format('Y-m-d')" />
            <x-field name="fecha_fin" label="Fecha de fin" type="date" :value="optional($orden->fecha_fin)->format('Y-m-d')" />

            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Observaciones</label>
                <input type="text" name="observaciones" value="{{ old('observaciones', $orden->observaciones) }}" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 focus:border-brand-500 focus:ring-2 focus:ring-brand-200 outline-none">
            </div>
        </div>

        <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
            <button type="submit" class="rounded-xl bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-brand-600/30 hover:bg-brand-700">{{ $editing ? 'Guardar cambios' : 'Crear orden' }}</button>
            <a href="{{ route('produccion.index') }}" class="rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50">Cancelar</a>
        </div>
    </form>
</div>
@endsection
