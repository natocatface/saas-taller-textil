@extends('layouts.app')

@section('title', 'Compra '.$compra->codigo)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center gap-2 text-sm text-slate-400">
        <a href="{{ route('dashboard') }}" class="hover:text-slate-600">Inicio</a><span>/</span>
        <a href="{{ route('compras.index') }}" class="hover:text-slate-600">Compras</a><span>/</span>
        <span class="text-slate-600 font-medium">{{ $compra->codigo }}</span>
    </div>

    <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-100 overflow-hidden">
        <div class="p-6 sm:p-8 border-b border-slate-100 flex items-start justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">{{ $compra->codigo }}</h1>
                <p class="text-slate-500 mt-1">{{ $compra->fecha?->format('d/m/Y') }} · {{ $compra->proveedor->razon_social ?? '—' }}</p>
            </div>
            <div class="text-right">
                <span class="text-xs font-semibold px-2.5 py-1 rounded-lg {{ $compra->estadoColor() }}">{{ \App\Models\Compra::ESTADOS[$compra->estado] }}</span>
                @if($compra->stock_aplicado)<p class="text-[11px] text-emerald-600 mt-1">Stock aplicado ✓</p>@endif
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="text-xs uppercase text-slate-400 bg-slate-50">
                    <tr>
                        <th class="text-left font-semibold px-6 py-3">Insumo</th>
                        <th class="text-center font-semibold px-4 py-3">Cant.</th>
                        <th class="text-right font-semibold px-4 py-3">Costo unit.</th>
                        <th class="text-right font-semibold px-6 py-3">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($compra->items as $it)
                        <tr>
                            <td class="px-6 py-3.5 font-medium text-slate-700">{{ $it->materiaPrima->nombre ?? '—' }} <span class="text-xs text-slate-400">{{ $it->materiaPrima->codigo ?? '' }}</span></td>
                            <td class="px-4 py-3.5 text-center text-slate-600">{{ rtrim(rtrim(number_format($it->cantidad, 2), '0'), '.') }}</td>
                            <td class="px-4 py-3.5 text-right text-slate-600">S/ {{ number_format($it->costo_unitario, 2) }}</td>
                            <td class="px-6 py-3.5 text-right font-semibold text-slate-700">S/ {{ number_format($it->subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-slate-50">
                        <td colspan="3" class="px-6 py-4 text-right font-semibold text-slate-600">Total</td>
                        <td class="px-6 py-4 text-right text-lg font-bold text-slate-800">S/ {{ number_format($compra->total, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="flex flex-wrap gap-3">
        <a href="{{ route('compras.comprobante', $compra) }}" target="_blank" class="rounded-xl bg-white border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50 inline-flex items-center gap-2">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247"/></svg>
            Imprimir
        </a>
        <a href="{{ route('compras.pdf', $compra) }}" class="rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-emerald-600/30 hover:bg-emerald-700 inline-flex items-center gap-2">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
            Descargar PDF
        </a>
        <a href="{{ route('compras.edit', $compra) }}" class="rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-brand-600/30 hover:bg-brand-700">Editar</a>
        <a href="{{ route('compras.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50">Volver</a>
    </div>
</div>
@endsection
