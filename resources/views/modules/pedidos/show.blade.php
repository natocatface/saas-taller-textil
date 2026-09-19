@extends('layouts.app')

@section('title', 'Pedido '.$pedido->codigo)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center gap-2 text-sm text-slate-400">
        <a href="{{ route('dashboard') }}" class="hover:text-slate-600">Inicio</a><span>/</span>
        <a href="{{ route('pedidos.index') }}" class="hover:text-slate-600">Pedidos</a><span>/</span>
        <span class="text-slate-600 font-medium">{{ $pedido->codigo }}</span>
    </div>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-bold text-slate-800">{{ $pedido->codigo }}</h1>
                <span class="text-xs font-semibold px-2.5 py-1 rounded-lg {{ $pedido->estadoColor() }}">{{ $pedido->estadoLabel() }}</span>
            </div>
            <p class="text-slate-500">Registrado el {{ $pedido->fecha_pedido?->format('d/m/Y') }}</p>
        </div>
        <a href="{{ route('pedidos.edit', $pedido) }}" class="rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-brand-600/30 hover:bg-brand-700">Editar pedido</a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-100">
            <p class="text-sm text-slate-400">Cliente</p>
            <p class="mt-1 font-semibold text-slate-800">{{ $pedido->cliente->nombre ?? '—' }}</p>
            <p class="text-xs text-slate-500">{{ $pedido->cliente->telefono ?? '' }}</p>
        </div>
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-100">
            <p class="text-sm text-slate-400">Fecha de entrega</p>
            <p class="mt-1 font-semibold text-slate-800">{{ $pedido->fecha_entrega?->format('d/m/Y') ?? 'Sin definir' }}</p>
        </div>
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-100">
            <p class="text-sm text-slate-400">Total prendas</p>
            <p class="mt-1 font-semibold text-slate-800">{{ $pedido->totalPrendas() }}</p>
        </div>
    </div>

    <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-100 overflow-hidden">
        <div class="p-6 pb-4"><h3 class="font-semibold text-slate-800">Detalle</h3></div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="text-xs uppercase text-slate-400 bg-slate-50">
                    <tr>
                        <th class="text-left font-semibold px-6 py-3">Producto</th>
                        <th class="text-center font-semibold px-4 py-3">Cantidad</th>
                        <th class="text-right font-semibold px-4 py-3">Precio unit.</th>
                        <th class="text-right font-semibold px-6 py-3">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($pedido->items as $it)
                        <tr>
                            <td class="px-6 py-3.5 font-medium text-slate-700">{{ $it->producto->nombre ?? '—' }} <span class="text-xs text-slate-400">{{ $it->producto->codigo ?? '' }}</span></td>
                            <td class="px-4 py-3.5 text-center text-slate-600">{{ $it->cantidad }}</td>
                            <td class="px-4 py-3.5 text-right text-slate-600">S/ {{ number_format($it->precio_unitario, 2) }}</td>
                            <td class="px-6 py-3.5 text-right font-semibold text-slate-700">S/ {{ number_format($it->subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-slate-50">
                        <td colspan="3" class="px-6 py-4 text-right font-semibold text-slate-600">Total</td>
                        <td class="px-6 py-4 text-right text-lg font-bold text-slate-800">S/ {{ number_format($pedido->total, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    @if ($pedido->observaciones)
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100">
            <p class="text-sm font-semibold text-slate-700 mb-1">Observaciones</p>
            <p class="text-slate-600">{{ $pedido->observaciones }}</p>
        </div>
    @endif

    @if ($pedido->ordenesProduccion->isNotEmpty())
        <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-100 overflow-hidden">
            <div class="p-6 pb-4"><h3 class="font-semibold text-slate-800">Órdenes de producción vinculadas</h3></div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="text-xs uppercase text-slate-400 bg-slate-50">
                        <tr>
                            <th class="text-left font-semibold px-6 py-3">Orden</th>
                            <th class="text-left font-semibold px-4 py-3">Etapa</th>
                            <th class="text-left font-semibold px-4 py-3">Operario</th>
                            <th class="text-left font-semibold px-6 py-3">Avance</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($pedido->ordenesProduccion as $op)
                            <tr>
                                <td class="px-6 py-3.5 font-semibold text-slate-700">{{ $op->codigo }}</td>
                                <td class="px-4 py-3.5"><span class="text-xs font-semibold px-2.5 py-1 rounded-lg {{ $op->etapaColor() }}">{{ $op->etapaLabel() }}</span></td>
                                <td class="px-4 py-3.5 text-slate-600">{{ $op->empleado->nombre ?? '—' }}</td>
                                <td class="px-6 py-3.5">
                                    <div class="flex items-center gap-2">
                                        <div class="h-2 w-24 rounded-full bg-slate-100 overflow-hidden"><div class="h-full rounded-full bg-brand-500" style="width: {{ $op->avance }}%"></div></div>
                                        <span class="text-xs text-slate-500">{{ $op->avance }}%</span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
