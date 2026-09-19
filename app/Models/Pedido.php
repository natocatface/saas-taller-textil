<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pedido extends Model
{
    protected $table = 'pedidos';

    protected $fillable = [
        'codigo', 'cliente_id', 'fecha_pedido', 'fecha_entrega',
        'estado', 'total', 'observaciones',
    ];

    protected $casts = [
        'fecha_pedido' => 'date',
        'fecha_entrega' => 'date',
        'total' => 'decimal:2',
    ];

    public const ESTADOS = [
        'pendiente' => 'Pendiente',
        'en_produccion' => 'En producción',
        'acabado' => 'Acabado',
        'entregado' => 'Entregado',
        'anulado' => 'Anulado',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PedidoItem::class);
    }

    public function ordenesProduccion(): HasMany
    {
        return $this->hasMany(OrdenProduccion::class);
    }

    public function estadoLabel(): string
    {
        return self::ESTADOS[$this->estado] ?? $this->estado;
    }

    public function estadoColor(): string
    {
        return [
            'pendiente' => 'bg-slate-100 text-slate-600',
            'en_produccion' => 'bg-brand-50 text-brand-600',
            'acabado' => 'bg-amber-50 text-amber-600',
            'entregado' => 'bg-emerald-50 text-emerald-600',
            'anulado' => 'bg-rose-50 text-rose-600',
        ][$this->estado] ?? 'bg-slate-100 text-slate-600';
    }

    public function totalPrendas(): int
    {
        return (int) $this->items->sum('cantidad');
    }
}
