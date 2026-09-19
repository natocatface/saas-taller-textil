<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MovimientoInventario extends Model
{
    protected $table = 'movimientos_inventario';

    protected $fillable = [
        'materia_prima_id', 'tipo', 'cantidad', 'stock_anterior',
        'stock_resultante', 'motivo', 'referencia', 'user_id', 'fecha',
    ];

    protected $casts = [
        'cantidad' => 'decimal:2',
        'stock_anterior' => 'decimal:2',
        'stock_resultante' => 'decimal:2',
        'fecha' => 'date',
    ];

    public const TIPOS = [
        'entrada' => 'Entrada',
        'salida' => 'Salida',
        'ajuste' => 'Ajuste',
    ];

    public function materiaPrima(): BelongsTo
    {
        return $this->belongsTo(MateriaPrima::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tipoColor(): string
    {
        return [
            'entrada' => 'bg-emerald-50 text-emerald-600',
            'salida' => 'bg-rose-50 text-rose-600',
            'ajuste' => 'bg-amber-50 text-amber-600',
        ][$this->tipo] ?? 'bg-slate-100 text-slate-600';
    }
}
