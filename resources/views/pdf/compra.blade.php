<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <style>
        * { font-family: DejaVu Sans, sans-serif; }
        body { color: #334155; font-size: 12px; margin: 0; }
        .muted { color: #64748b; }
        .small { font-size: 10px; }
        .right { text-align: right; }
        .center { text-align: center; }
        .bold { font-weight: bold; }
        h1 { font-size: 15px; margin: 0; color: #1e293b; }

        table { width: 100%; border-collapse: collapse; }
        .header td { vertical-align: top; }
        .brand { width: 40px; height: 40px; background: #4f46e5; color: #fff; text-align: center; font-size: 20px; font-weight: bold; border-radius: 8px; }
        .doc-box { border: 1px solid #cbd5e1; border-radius: 8px; padding: 8px 12px; display: inline-block; }
        .doc-box .label { font-size: 9px; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; }
        .doc-box .num { font-size: 15px; font-weight: bold; color: #1e293b; }

        .section { margin-top: 18px; }
        .divider { border-top: 1px solid #e2e8f0; margin: 14px 0; }
        .label { font-size: 9px; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; margin-bottom: 3px; }

        .items th { background: #f1f5f9; color: #64748b; font-size: 9px; text-transform: uppercase; text-align: left; padding: 7px 8px; border-bottom: 1px solid #e2e8f0; }
        .items td { padding: 8px; border-bottom: 1px solid #eef2f7; }

        .totals { width: 240px; float: right; margin-top: 12px; }
        .totals td { padding: 4px 0; }
        .total-row td { border-top: 1px solid #cbd5e1; padding-top: 8px; font-size: 14px; font-weight: bold; color: #1e293b; }

        .received { color: #059669; }
        .pending { color: #d97706; }
        .void { color: #e11d48; }

        .footer { margin-top: 40px; text-align: center; color: #94a3b8; font-size: 10px; }
    </style>
</head>
<body>

    <table class="header">
        <tr>
            <td style="width: 60%;">
                <table>
                    <tr>
                        <td style="width: 48px;"><div class="brand">T</div></td>
                        <td style="padding-left: 10px;">
                            <h1>{{ $config->empresa }}</h1>
                            @if($config->ruc)<div class="muted small">RUC: {{ $config->ruc }}</div>@endif
                            @if($config->direccion)<div class="muted small">{{ $config->direccion }}</div>@endif
                            @if($config->telefono || $config->email)
                                <div class="muted small">{{ $config->telefono }}@if($config->telefono && $config->email) · @endif{{ $config->email }}</div>
                            @endif
                        </td>
                    </tr>
                </table>
            </td>
            <td class="right" style="width: 40%;">
                <div class="doc-box">
                    <div class="label">Orden de compra</div>
                    <div class="num">{{ $compra->codigo }}</div>
                </div>
                <div class="muted small" style="margin-top: 6px;">Fecha: {{ $compra->fecha?->format('d/m/Y') }}</div>
            </td>
        </tr>
    </table>

    <div class="divider"></div>

    <table>
        <tr>
            <td style="width: 60%;">
                <div class="label">Proveedor</div>
                <div class="bold">{{ $compra->proveedor->razon_social ?? '—' }}</div>
                @if($compra->proveedor?->ruc)<div class="muted small">RUC: {{ $compra->proveedor->ruc }}</div>@endif
                @if($compra->proveedor?->telefono)<div class="muted small">{{ $compra->proveedor->telefono }}</div>@endif
            </td>
            <td class="right" style="width: 40%;">
                <div class="label">Estado</div>
                @php $cls = $compra->estado==='recibida' ? 'received' : ($compra->estado==='anulada' ? 'void' : 'pending'); @endphp
                <span class="bold {{ $cls }}">{{ \App\Models\Compra::ESTADOS[$compra->estado] }}</span>
            </td>
        </tr>
    </table>

    <table class="items section">
        <thead>
            <tr>
                <th>Insumo</th>
                <th class="center" style="width: 60px;">Cant.</th>
                <th class="right" style="width: 90px;">Costo unit.</th>
                <th class="right" style="width: 90px;">Importe</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($compra->items as $it)
                <tr>
                    <td>{{ $it->materiaPrima->nombre ?? '—' }} <span class="muted small">{{ $it->materiaPrima->codigo ?? '' }}</span></td>
                    <td class="center">{{ rtrim(rtrim(number_format($it->cantidad,2),'0'),'.') }}</td>
                    <td class="right">{{ number_format($it->costo_unitario, 2) }}</td>
                    <td class="right">{{ number_format($it->subtotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals">
        <tr class="total-row">
            <td>TOTAL</td>
            <td class="right">{{ $config->moneda }} {{ number_format($compra->total, 2) }}</td>
        </tr>
    </table>

    <div style="clear: both;"></div>

    @if($compra->observaciones)
        <div class="section">
            <div class="label">Observaciones</div>
            <div class="muted">{{ $compra->observaciones }}</div>
        </div>
    @endif

    <div class="footer">Orden de compra · {{ $config->empresa }}</div>
</body>
</html>
