<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrdenProduccion extends Model
{
    protected $table = 'ordenes_produccion';

    protected $fillable = [
        'codigo', 'pedido_id', 'producto_id', 'empleado_id', 'cantidad',
        'etapa', 'avance', 'estado', 'fecha_inicio', 'fecha_fin', 'observaciones',
    ];

    protected $casts = [
        'cantidad' => 'integer',
        'avance' => 'integer',
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];

    public const ETAPAS = [
        'corte' => 'Corte',
        'confeccion' => 'Confección',
        'acabado' => 'Acabado',
        'control' => 'Control de Calidad',
        'terminado' => 'Terminado',
    ];

    public const ESTADOS = [
        'en_proceso' => 'En proceso',
        'pausado' => 'Pausado',
        'terminado' => 'Terminado',
    ];

    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class);
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class);
    }

    public function etapaLabel(): string
    {
        return self::ETAPAS[$this->etapa] ?? $this->etapa;
    }

    public function etapaColor(): string
    {
        return [
            'corte' => 'bg-brand-50 text-brand-600',
            'confeccion' => 'bg-emerald-50 text-emerald-600',
            'acabado' => 'bg-amber-50 text-amber-600',
            'control' => 'bg-purple-50 text-purple-600',
            'terminado' => 'bg-slate-100 text-slate-600',
        ][$this->etapa] ?? 'bg-slate-100 text-slate-600';
    }
}
