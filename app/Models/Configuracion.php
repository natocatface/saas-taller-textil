<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    protected $table = 'configuraciones';

    protected $fillable = [
        'empresa', 'ruc', 'direccion', 'telefono',
        'email', 'moneda', 'igv', 'logo',
    ];

    protected $casts = [
        'igv' => 'decimal:2',
    ];

    public static function actual(): self
    {
        return static::firstOrCreate(['id' => 1], [
            'empresa' => 'Taller Textil',
            'moneda' => 'PEN',
            'igv' => 18,
        ]);
    }
}
