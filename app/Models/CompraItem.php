<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompraItem extends Model
{
    protected $table = 'compra_items';

    protected $fillable = [
        'compra_id', 'materia_prima_id', 'cantidad', 'costo_unitario', 'subtotal',
    ];

    protected $casts = [
        'cantidad' => 'decimal:2',
        'costo_unitario' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function compra(): BelongsTo
    {
        return $this->belongsTo(Compra::class);
    }

    public function materiaPrima(): BelongsTo
    {
        return $this->belongsTo(MateriaPrima::class);
    }
}
