<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos';

    protected $fillable = [
        'codigo', 'nombre', 'categoria', 'talla', 'color',
        'precio', 'costo', 'stock', 'estado',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'costo' => 'decimal:2',
        'stock' => 'integer',
        'estado' => 'boolean',
    ];
}
