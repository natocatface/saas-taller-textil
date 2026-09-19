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
        .brand {
            width: 40px; height: 40px; background: #4f46e5; color: #fff;
            text-align: center; font-size: 20px; font-weight: bold;
            border-radius: 8px;
        }
        .doc-box { border: 1px solid #cbd5e1; border-radius: 8px; padding: 8px 12px; display: inline-block; }
        .doc-box .label { font-size: 9px; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; }
        .doc-box .num { font-size: 15px; font-weight: bold; color: #1e293b; }

        .section { margin-top: 18px; }
        .divider { border-top: 1px solid #e2e8f0; margin: 14px 0; }
        .label { font-size: 9px; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; margin-bottom: 3px; }

        .items th {
            background: #f1f5f9; color: #64748b; font-size: 9px; text-transform: uppercase;
            text-align: left; padding: 7px 8px; border-bottom: 1px solid #e2e8f0;
        }
        .items td { padding: 8px; border-bottom: 1px solid #eef2f7; }

        .totals { width: 240px; float: right; margin-top: 12px; }
        .totals td { padding: 4px 0; }
        .total-row td { border-top: 1px solid #cbd5e1; padding-top: 8px; font-size: 14px; font-weight: bold; color: #1e293b; }

        .badge { padding: 3px 8px; border-radius: 6px; font-size: 10px; font-weight: bold; }
        .paid { color: #059669; }
        .pending { color: #d97706; }
        .void { color: #e11d48; }

        .footer { margin-top: 40px; text-align: center; color: #94a3b8; font-size: 10px; }
    </style>
</head>
<body>

    <!-- Encabezado -->
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
                    <div class="label">{{ $venta->tipo_comprobante === 'factura' ? 'Factura' : 'Boleta de venta' }}</div>
                    <div class="num">{{ $venta->codigo }}</div>
                </div>
                <div class="muted small" style="margin-top: 6px;">Fecha: {{ $venta->fecha?->format('d/m/Y') }}</div>
            </td>
        </tr>
    </table>

    <div class="divider"></div>

    <!-- Cliente -->
    <table>
        <tr>
            <td style="width: 60%;">
                <div class="label">Cliente</div>
                <div class="bold">{{ $venta->cliente->nombre ?? '—' }}</div>
                <div class="muted small">{{ $venta->cliente->tipo_documento ?? '' }} {{ $venta->cliente->numero_documento ?? '' }}</div>
                @if($venta->cliente?->direccion)<div class="muted small">{{ $venta->cliente->direccion }}</div>@endif
            </td>
            <td class="right" style="width: 40%;">
                <div class="label">Estado</div>
                @php $cls = $venta->estado==='pagado' ? 'paid' : ($venta->estado==='anulado' ? 'void' : 'pending'); @endphp
                <span class="bold {{ $cls }}">{{ \App\Models\Venta::ESTADOS[$venta->estado] }}</span>
            </td>
        </tr>
    </table>

    <!-- Detalle -->
    <table class="items section">
        <thead>
            <tr>
                <th>Descripción</th>
                <th class="center" style="width: 60px;">Cant.</th>
                <th class="right" style="width: 90px;">P. unit.</th>
                <th class="right" style="width: 90px;">Importe</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($venta->items as $it)
                <tr>
                    <td>{{ $it->producto->nombre ?? '—' }} <span class="muted small">{{ $it->producto->codigo ?? '' }}</span></td>
                    <td class="center">{{ $it->cantidad }}</td>
                    <td class="right">{{ number_format($it->precio_unitario, 2) }}</td>
                    <td class="right">{{ number_format($it->subtotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Totales -->
    <table class="totals">
        <tr>
            <td class="muted">Subtotal</td>
            <td class="right">{{ $config->moneda }} {{ number_format($venta->subtotal, 2) }}</td>
        </tr>
        <tr>
            <td class="muted">IGV ({{ rtrim(rtrim(number_format($config->igv,2),'0'),'.') }}%)</td>
            <td class="right">{{ $config->moneda }} {{ number_format($venta->igv, 2) }}</td>
        </tr>
        <tr class="total-row">
            <td>TOTAL</td>
            <td class="right">{{ $config->moneda }} {{ number_format($venta->total, 2) }}</td>
        </tr>
    </table>

    <div style="clear: both;"></div>

    @if($venta->observaciones)
        <div class="section">
            <div class="label">Observaciones</div>
            <div class="muted">{{ $venta->observaciones }}</div>
        </div>
    @endif

    <div class="footer">Representación impresa del comprobante · {{ $config->empresa }}</div>
</body>
</html>
