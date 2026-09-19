<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Venta extends Model
{
    protected $table = 'ventas';

    protected $fillable = [
        'codigo', 'cliente_id', 'pedido_id', 'fecha', 'tipo_comprobante',
        'estado', 'subtotal', 'igv', 'total', 'observaciones',
    ];

    protected $casts = [
        'fecha' => 'date',
        'subtotal' => 'decimal:2',
        'igv' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public const IGV = 0.18;

    public const ESTADOS = [
        'pagado' => 'Pagado',
        'pendiente' => 'Pendiente',
        'anulado' => 'Anulado',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(VentaItem::class);
    }

    public function estadoColor(): string
    {
        return [
            'pagado' => 'bg-emerald-50 text-emerald-600',
            'pendiente' => 'bg-amber-50 text-amber-600',
            'anulado' => 'bg-rose-50 text-rose-600',
        ][$this->estado] ?? 'bg-slate-100 text-slate-600';
    }
}
