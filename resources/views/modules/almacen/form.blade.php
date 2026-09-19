@extends('layouts.app')

@section('title', 'Nuevo movimiento')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center gap-2 text-sm text-slate-400">
        <a href="{{ route('dashboard') }}" class="hover:text-slate-600">Inicio</a><span>/</span>
        <a href="{{ route('almacen.index') }}" class="hover:text-slate-600">Almacén</a><span>/</span>
        <span class="text-slate-600 font-medium">Nuevo movimiento</span>
    </div>

    <h1 class="text-2xl font-bold text-slate-800">Nuevo movimiento de inventario</h1>

    <div class="rounded-xl bg-brand-50 border border-brand-100 px-4 py-3 text-sm text-brand-700">
        <strong>Entrada</strong> suma al stock · <strong>Salida</strong> resta del stock · <strong>Ajuste</strong> fija el stock al valor indicado.
    </div>

    <form method="POST" action="{{ route('almacen.store') }}" class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-100 p-6 sm:p-8 space-y-5">
        @csrf
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1.5">Insumo <span class="text-rose-500">*</span></label>
            <select name="materia_prima_id" required onchange="mostrarStock(this)" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 focus:border-brand-500 focus:ring-2 focus:ring-brand-200 outline-none">
                <option value="">— Seleccione un insumo —</option>
                @foreach($materias as $m)
                    <option value="{{ $m->id }}" data-stock="{{ $m->stock }}" data-unidad="{{ $m->unidad }}" @selected(old('materia_prima_id')==$m->id)>{{ $m->nombre }} ({{ $m->codigo }})</option>
                @endforeach
            </select>
            <p id="stockActual" class="mt-1.5 text-xs text-slate-500 hidden">Stock actual: <span class="font-semibold text-slate-700"></span></p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Tipo de movimiento <span class="text-rose-500">*</span></label>
                <select name="tipo" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 focus:border-brand-500 focus:ring-2 focus:ring-brand-200 outline-none">
                    @foreach(\App\Models\MovimientoInventario::TIPOS as $val=>$lbl)
                        <option value="{{ $val }}" @selected(old('tipo','entrada')===$val)>{{ $lbl }}</option>
                    @endforeach
                </select>
            </div>
            <x-field name="cantidad" label="Cantidad" type="number" step="0.01" :value="old('cantidad')" required />
            <x-field name="fecha" label="Fecha" type="date" :value="old('fecha', now()->format('Y-m-d'))" required />
            <x-field name="referencia" label="Referencia" :value="old('referencia')" placeholder="Ej. COM-0003, guía…" />
            <div class="sm:col-span-2">
                <x-field name="motivo" label="Motivo" :value="old('motivo')" placeholder="Ej. Recepción de compra, consumo en producción…" />
            </div>
        </div>

        <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
            <button type="submit" class="rounded-xl bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-brand-600/30 hover:bg-brand-700">Registrar movimiento</button>
            <a href="{{ route('almacen.index') }}" class="rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50">Cancelar</a>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    function mostrarStock(sel) {
        const opt = sel.options[sel.selectedIndex];
        const box = document.getElementById('stockActual');
        if (opt && opt.dataset.stock !== undefined && opt.value) {
            box.querySelector('span').textContent = parseFloat(opt.dataset.stock) + ' ' + (opt.dataset.unidad || '');
            box.classList.remove('hidden');
        } else {
            box.classList.add('hidden');
        }
    }
</script>
@endsection
