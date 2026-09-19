<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Compra extends Model
{
    protected $table = 'compras';

    protected $fillable = [
        'codigo', 'proveedor_id', 'fecha', 'estado',
        'stock_aplicado', 'total', 'observaciones',
    ];

    protected $casts = [
        'fecha' => 'date',
        'stock_aplicado' => 'boolean',
        'total' => 'decimal:2',
    ];

    public const ESTADOS = [
        'pendiente' => 'Pendiente',
        'recibida' => 'Recibida',
        'anulada' => 'Anulada',
    ];

    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CompraItem::class);
    }

    public function estadoColor(): string
    {
        return [
            'pendiente' => 'bg-amber-50 text-amber-600',
            'recibida' => 'bg-emerald-50 text-emerald-600',
            'anulada' => 'bg-rose-50 text-rose-600',
        ][$this->estado] ?? 'bg-slate-100 text-slate-600';
    }
}
