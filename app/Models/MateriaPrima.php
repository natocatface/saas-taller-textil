<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MateriaPrima extends Model
{
    protected $table = 'materia_primas';

    protected $fillable = [
        'codigo', 'nombre', 'tipo', 'unidad', 'stock',
        'stock_minimo', 'costo_unitario', 'proveedor_id', 'estado',
    ];

    protected $casts = [
        'stock' => 'decimal:2',
        'stock_minimo' => 'decimal:2',
        'costo_unitario' => 'decimal:2',
        'estado' => 'boolean',
    ];

    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function getStockBajoAttribute(): bool
    {
        return $this->stock <= $this->stock_minimo;
    }
}
