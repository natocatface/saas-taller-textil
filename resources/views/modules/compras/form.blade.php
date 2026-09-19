@extends('layouts.app')

@php
    $editing = $compra->exists;
    $initial = old('items', $editing ? $compra->items->map(fn($i)=>[
        'materia_prima_id'=>$i->materia_prima_id,'cantidad'=>$i->cantidad,'costo_unitario'=>$i->costo_unitario
    ])->values()->all() : []);
@endphp
@section('title', $editing ? 'Editar compra' : 'Nueva compra')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center gap-2 text-sm text-slate-400">
        <a href="{{ route('dashboard') }}" class="hover:text-slate-600">Inicio</a><span>/</span>
        <a href="{{ route('compras.index') }}" class="hover:text-slate-600">Compras</a><span>/</span>
        <span class="text-slate-600 font-medium">{{ $editing ? $compra->codigo : 'Nueva' }}</span>
    </div>

    <h1 class="text-2xl font-bold text-slate-800">{{ $editing ? 'Editar compra '.$compra->codigo : 'Nueva compra' }}</h1>

    @if ($errors->any())
        <div class="rounded-xl bg-rose-50 border border-rose-200 px-4 py-3 text-sm text-rose-700">{{ $errors->first() }}</div>
    @endif

    <div class="rounded-xl bg-amber-50 border border-amber-200 px-4 py-3 text-sm text-amber-700">
        Al guardar con estado <strong>Recibida</strong>, el stock de cada insumo se incrementará automáticamente.
    </div>

    <form method="POST" action="{{ $editing ? route('compras.update', $compra) : route('compras.store') }}" class="space-y-6">
        @csrf
        @if($editing) @method('PUT') @endif

        <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-100 p-6 sm:p-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Proveedor <span class="text-rose-500">*</span></label>
                    <select name="proveedor_id" required class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 focus:border-brand-500 focus:ring-2 focus:ring-brand-200 outline-none">
                        <option value="">— Seleccione un proveedor —</option>
                        @foreach($proveedores as $p)
                            <option value="{{ $p->id }}" @selected(old('proveedor_id', $compra->proveedor_id)==$p->id)>{{ $p->razon_social }}</option>
                        @endforeach
                    </select>
                </div>
                <x-field name="fecha" label="Fecha" type="date" :value="optional($compra->fecha)->format('Y-m-d') ?: now()->format('Y-m-d')" required />
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Estado <span class="text-rose-500">*</span></label>
                    <select name="estado" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 focus:border-brand-500 focus:ring-2 focus:ring-brand-200 outline-none">
                        @foreach(\App\Models\Compra::ESTADOS as $val=>$lbl)
                            <option value="{{ $val }}" @selected(old('estado', $compra->estado ?: 'pendiente')===$val)>{{ $lbl }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Observaciones</label>
                    <input type="text" name="observaciones" value="{{ old('observaciones', $compra->observaciones) }}" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 focus:border-brand-500 focus:ring-2 focus:ring-brand-200 outline-none">
                </div>
            </div>
        </div>

        <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-100 overflow-hidden">
            <div class="flex items-center justify-between p-6 pb-4">
                <h3 class="font-semibold text-slate-800">Insumos</h3>
                <button type="button" onclick="addRow()" class="rounded-xl bg-brand-50 px-3 py-2 text-sm font-semibold text-brand-600 hover:bg-brand-100">+ Agregar insumo</button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="text-xs uppercase text-slate-400 bg-slate-50">
                        <tr>
                            <th class="text-left font-semibold px-6 py-3">Insumo</th>
                            <th class="text-center font-semibold px-4 py-3 w-28">Cantidad</th>
                            <th class="text-right font-semibold px-4 py-3 w-36">Costo unit.</th>
                            <th class="text-right font-semibold px-4 py-3 w-36">Subtotal</th>
                            <th class="px-4 py-3 w-12"></th>
                        </tr>
                    </thead>
                    <tbody id="itemsBody" class="divide-y divide-slate-100"></tbody>
                    <tfoot>
                        <tr class="bg-slate-50">
                            <td colspan="3" class="px-6 py-4 text-right font-semibold text-slate-600">Total</td>
                            <td class="px-4 py-4 text-right text-lg font-bold text-slate-800" id="totalDisplay">S/ 0.00</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="rounded-xl bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-brand-600/30 hover:bg-brand-700">{{ $editing ? 'Guardar cambios' : 'Registrar compra' }}</button>
            <a href="{{ route('compras.index') }}" class="rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50">Cancelar</a>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    const materias = @json($materias->map(fn($m)=>['id'=>$m->id,'nombre'=>$m->nombre.' — '.$m->codigo,'costo'=>(float)$m->costo_unitario])->values());
    const initialItems = @json($initial);
    let idx = 0;

    function optionsHtml(selected) {
        let h = '<option value="">— Seleccione —</option>';
        materias.forEach(m => { h += `<option value="${m.id}" data-costo="${m.costo}" ${m.id==selected?'selected':''}>${m.nombre}</option>`; });
        return h;
    }

    function addRow(item) {
        const i = idx++;
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td class="px-6 py-3"><select name="items[${i}][materia_prima_id]" onchange="onMateria(this)" required class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 focus:border-brand-500 focus:ring-2 focus:ring-brand-200 outline-none">${optionsHtml(item?.materia_prima_id)}</select></td>
            <td class="px-4 py-3"><input type="number" step="0.01" name="items[${i}][cantidad]" min="0.01" value="${item?.cantidad ?? 1}" oninput="recalc()" class="w-full text-center rounded-lg border border-slate-300 px-2 py-2 focus:border-brand-500 focus:ring-2 focus:ring-brand-200 outline-none"></td>
            <td class="px-4 py-3"><input type="number" step="0.01" min="0" name="items[${i}][costo_unitario]" value="${item?.costo_unitario ?? '0.00'}" oninput="recalc()" class="w-full text-right rounded-lg border border-slate-300 px-2 py-2 focus:border-brand-500 focus:ring-2 focus:ring-brand-200 outline-none"></td>
            <td class="px-4 py-3 text-right font-semibold text-slate-700 subtotal">S/ 0.00</td>
            <td class="px-4 py-3 text-center"><button type="button" onclick="this.closest('tr').remove(); recalc();" class="p-1.5 rounded-lg text-slate-400 hover:bg-rose-50 hover:text-rose-600"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button></td>`;
        document.getElementById('itemsBody').appendChild(tr);
        recalc();
    }

    function onMateria(sel) {
        const costo = sel.options[sel.selectedIndex]?.dataset.costo;
        const input = sel.closest('tr').querySelector('input[name*="[costo_unitario]"]');
        if (costo && parseFloat(input.value) === 0) input.value = parseFloat(costo).toFixed(2);
        recalc();
    }

    function recalc() {
        let total = 0;
        document.querySelectorAll('#itemsBody tr').forEach(row => {
            const c = parseFloat(row.querySelector('input[name*="[cantidad]"]').value) || 0;
            const p = parseFloat(row.querySelector('input[name*="[costo_unitario]"]').value) || 0;
            const s = c * p;
            row.querySelector('.subtotal').textContent = 'S/ ' + s.toFixed(2);
            total += s;
        });
        document.getElementById('totalDisplay').textContent = 'S/ ' + total.toFixed(2);
    }

    if (initialItems.length) initialItems.forEach(it => addRow(it)); else addRow();
</script>
@endsection
