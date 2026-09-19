<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Orden de compra {{ $compra->codigo }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = { theme: { extend: {
            fontFamily: { sans: ['Inter','sans-serif'] },
            colors: { brand: { 50:'#eef2ff',100:'#e0e7ff',500:'#6366f1',600:'#4f46e5',700:'#4338ca',800:'#3730a3' } }
        }}}
    </script>
    <style>
        body{font-family:'Inter',sans-serif}
        @media print {
            .no-print{ display:none !important; }
            body{ background:#fff !important; }
            .sheet{ box-shadow:none !important; margin:0 !important; border:0 !important; }
        }
        @page { size: A4; margin: 14mm; }
    </style>
</head>
<body class="bg-slate-100 text-slate-700">

    <div class="no-print sticky top-0 bg-white border-b border-slate-200 px-6 py-3 flex items-center justify-between">
        <a href="{{ route('compras.show', $compra) }}" class="text-sm font-medium text-slate-600 hover:text-slate-800 inline-flex items-center gap-2">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
            Volver
        </a>
        <button onclick="window.print()" class="rounded-xl bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-brand-600/30 hover:bg-brand-700 inline-flex items-center gap-2">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247"/></svg>
            Imprimir / Guardar PDF
        </button>
    </div>

    <div class="max-w-3xl mx-auto my-8 px-4">
        <div class="sheet bg-white rounded-2xl shadow-sm ring-1 ring-slate-200 p-8 sm:p-10">

            <div class="flex items-start justify-between gap-6 pb-6 border-b border-slate-200">
                <div class="flex items-start gap-4">
                    <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-brand-500 to-purple-600 flex items-center justify-center text-white shrink-0">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h12A2.25 2.25 0 0120.25 6v2.25M3.75 6v12A2.25 2.25 0 006 20.25h12a2.25 2.25 0 002.25-2.25V6M3.75 12h16.5"/></svg>
                    </div>
                    <div>
                        <h1 class="text-lg font-extrabold text-slate-800">{{ $config->empresa }}</h1>
                        @if($config->ruc)<p class="text-sm text-slate-500">RUC: {{ $config->ruc }}</p>@endif
                        @if($config->direccion)<p class="text-sm text-slate-500">{{ $config->direccion }}</p>@endif
                        @if($config->telefono || $config->email)
                            <p class="text-sm text-slate-500">{{ $config->telefono }}@if($config->telefono && $config->email) · @endif{{ $config->email }}</p>
                        @endif
                    </div>
                </div>
                <div class="text-right shrink-0">
                    <div class="inline-block rounded-xl border border-slate-300 px-4 py-3">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Orden de compra</p>
                        <p class="text-lg font-extrabold text-slate-800">{{ $compra->codigo }}</p>
                    </div>
                    <p class="mt-2 text-sm text-slate-500">Fecha: {{ $compra->fecha?->format('d/m/Y') }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 py-6 border-b border-slate-200">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1">Proveedor</p>
                    <p class="font-semibold text-slate-800">{{ $compra->proveedor->razon_social ?? '—' }}</p>
                    @if($compra->proveedor?->ruc)<p class="text-sm text-slate-500">RUC: {{ $compra->proveedor->ruc }}</p>@endif
                    @if($compra->proveedor?->telefono)<p class="text-sm text-slate-500">{{ $compra->proveedor->telefono }}</p>@endif
                </div>
                <div class="sm:text-right">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1">Estado</p>
                    <p class="font-semibold {{ $compra->estado==='recibida' ? 'text-emerald-600' : ($compra->estado==='anulada' ? 'text-rose-600' : 'text-amber-600') }}">{{ \App\Models\Compra::ESTADOS[$compra->estado] }}</p>
                </div>
            </div>

            <table class="w-full text-sm mt-6">
                <thead>
                    <tr class="text-xs uppercase text-slate-400 border-b border-slate-200">
                        <th class="text-left font-semibold py-2">Insumo</th>
                        <th class="text-center font-semibold py-2 w-20">Cant.</th>
                        <th class="text-right font-semibold py-2 w-28">Costo unit.</th>
                        <th class="text-right font-semibold py-2 w-28">Importe</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($compra->items as $it)
                        <tr class="border-b border-slate-100">
                            <td class="py-3 text-slate-700">{{ $it->materiaPrima->nombre ?? '—' }} <span class="text-xs text-slate-400">{{ $it->materiaPrima->codigo ?? '' }}</span></td>
                            <td class="py-3 text-center text-slate-600">{{ rtrim(rtrim(number_format($it->cantidad,2),'0'),'.') }}</td>
                            <td class="py-3 text-right text-slate-600">{{ number_format($it->costo_unitario, 2) }}</td>
                            <td class="py-3 text-right font-medium text-slate-700">{{ number_format($it->subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="flex justify-end mt-6">
                <div class="w-64 space-y-2 text-sm">
                    <div class="flex justify-between pt-2 border-t border-slate-200"><span class="font-bold text-slate-800">TOTAL</span><span class="text-lg font-extrabold text-slate-800">{{ $config->moneda }} {{ number_format($compra->total, 2) }}</span></div>
                </div>
            </div>

            @if($compra->observaciones)
                <div class="mt-8 pt-4 border-t border-slate-200">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1">Observaciones</p>
                    <p class="text-sm text-slate-600">{{ $compra->observaciones }}</p>
                </div>
            @endif

            <p class="mt-10 text-center text-xs text-slate-400">Orden de compra · {{ $config->empresa }}</p>
        </div>
    </div>
</body>
</html>
