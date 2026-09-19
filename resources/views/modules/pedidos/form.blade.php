@extends('layouts.app')

@php
    $editing = $pedido->exists;
    $initial = old('items', $editing ? $pedido->items->map(fn($i)=>[
        'producto_id'=>$i->producto_id,'cantidad'=>$i->cantidad,'precio_unitario'=>$i->precio_unitario
    ])->values()->all() : []);
@endphp
@section('title', $editing ? 'Editar pedido' : 'Nuevo pedido')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center gap-2 text-sm text-slate-400">
        <a href="{{ route('dashboard') }}" class="hover:text-slate-600">Inicio</a><span>/</span>
        <a href="{{ route('pedidos.index') }}" class="hover:text-slate-600">Pedidos</a><span>/</span>
        <span class="text-slate-600 font-medium">{{ $editing ? $pedido->codigo : 'Nuevo' }}</span>
    </div>

    <h1 class="text-2xl font-bold text-slate-800">{{ $editing ? 'Editar pedido '.$pedido->codigo : 'Nuevo pedido' }}</h1>

    @if ($errors->any())
        <div class="rounded-xl bg-rose-50 border border-rose-200 px-4 py-3 text-sm text-rose-700">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ $editing ? route('pedidos.update', $pedido) : route('pedidos.store') }}" class="space-y-6">
        @csrf
        @if($editing) @method('PUT') @endif

        <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-100 p-6 sm:p-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Cliente <span class="text-rose-500">*</span></label>
                    <select name="cliente_id" required class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 focus:border-brand-500 focus:ring-2 focus:ring-brand-200 outline-none">
                        <option value="">— Seleccione un cliente —</option>
                        @foreach($clientes as $c)
                            <option value="{{ $c->id }}" @selected(old('cliente_id', $pedido->cliente_id)==$c->id)>{{ $c->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <x-field name="fecha_pedido" label="Fecha del pedido" type="date" :value="optional($pedido->fecha_pedido)->format('Y-m-d') ?: now()->format('Y-m-d')" required />
                <x-field name="fecha_entrega" label="Fecha de entrega" type="date" :value="optional($pedido->fecha_entrega)->format('Y-m-d')" />
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Estado <span class="text-rose-500">*</span></label>
                    <select name="estado" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 focus:border-brand-500 focus:ring-2 focus:ring-brand-200 outline-none">
                        @foreach(\App\Models\Pedido::ESTADOS as $val=>$lbl)
                            <option value="{{ $val }}" @selected(old('estado', $pedido->estado ?: 'pendiente')===$val)>{{ $lbl }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Observaciones</label>
                    <input type="text" name="observaciones" value="{{ old('observaciones', $pedido->observaciones) }}" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 focus:border-brand-500 focus:ring-2 focus:ring-brand-200 outline-none">
                </div>
            </div>
        </div>

        <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-100 overflow-hidden">
            <div class="flex items-center justify-between p-6 pb-4">
                <h3 class="font-semibold text-slate-800">Detalle del pedido</h3>
                <button type="button" onclick="addRow()" class="rounded-xl bg-brand-50 px-3 py-2 text-sm font-semibold text-brand-600 hover:bg-brand-100">+ Agregar producto</button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="text-xs uppercase text-slate-400 bg-slate-50">
                        <tr>
                            <th class="text-left font-semibold px-6 py-3">Producto</th>
                            <th class="text-center font-semibold px-4 py-3 w-28">Cantidad</th>
                            <th class="text-right font-semibold px-4 py-3 w-36">Precio unit.</th>
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
            <button type="submit" class="rounded-xl bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-brand-600/30 hover:bg-brand-700">{{ $editing ? 'Guardar cambios' : 'Registrar pedido' }}</button>
            <a href="{{ route('pedidos.index') }}" class="rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50">Cancelar</a>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    const productos = @json($productos->map(fn($p)=>['id'=>$p->id,'nombre'=>$p->nombre.' — '.$p->codigo,'precio'=>(float)$p->precio])->values());
    const initialItems = @json($initial);
    let idx = 0;

    function optionsHtml(selected) {
        let h = '<option value="">— Seleccione —</option>';
        productos.forEach(p => {
            h += `<option value="${p.id}" data-precio="${p.precio}" ${p.id==selected?'selected':''}>${p.nombre}</option>`;
        });
        return h;
    }

    function addRow(item) {
        const i = idx++;
        const tr = document.createElement('tr');
        tr.className = 'align-top';
        tr.innerHTML = `
            <td class="px-6 py-3">
                <select name="items[${i}][producto_id]" onchange="onProducto(this)" required
                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 focus:border-brand-500 focus:ring-2 focus:ring-brand-200 outline-none">
                    ${optionsHtml(item?.producto_id)}
                </select>
            </td>
            <td class="px-4 py-3">
                <input type="number" name="items[${i}][cantidad]" min="1" value="${item?.cantidad ?? 1}" oninput="recalc()"
                    class="w-full text-center rounded-lg border border-slate-300 px-2 py-2 focus:border-brand-500 focus:ring-2 focus:ring-brand-200 outline-none">
            </td>
            <td class="px-4 py-3">
                <input type="number" step="0.01" min="0" name="items[${i}][precio_unitario]" value="${item?.precio_unitario ?? '0.00'}" oninput="recalc()"
                    class="w-full text-right rounded-lg border border-slate-300 px-2 py-2 focus:border-brand-500 focus:ring-2 focus:ring-brand-200 outline-none">
            </td>
            <td class="px-4 py-3 text-right font-semibold text-slate-700 subtotal">S/ 0.00</td>
            <td class="px-4 py-3 text-center">
                <button type="button" onclick="this.closest('tr').remove(); recalc();" class="p-1.5 rounded-lg text-slate-400 hover:bg-rose-50 hover:text-rose-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </td>`;
        document.getElementById('itemsBody').appendChild(tr);
        recalc();
    }

    function onProducto(sel) {
        const precio = sel.options[sel.selectedIndex]?.dataset.precio;
        const row = sel.closest('tr');
        const precioInput = row.querySelector('input[name*="[precio_unitario]"]');
        if (precio && parseFloat(precioInput.value) === 0) precioInput.value = parseFloat(precio).toFixed(2);
        recalc();
    }

    function recalc() {
        let total = 0;
        document.querySelectorAll('#itemsBody tr').forEach(row => {
            const cant = parseFloat(row.querySelector('input[name*="[cantidad]"]').value) || 0;
            const precio = parseFloat(row.querySelector('input[name*="[precio_unitario]"]').value) || 0;
            const sub = cant * precio;
            row.querySelector('.subtotal').textContent = 'S/ ' + sub.toFixed(2);
            total += sub;
        });
        document.getElementById('totalDisplay').textContent = 'S/ ' + total.toFixed(2);
    }

    if (initialItems.length) initialItems.forEach(it => addRow(it));
    else addRow();
</script>
@endsection
