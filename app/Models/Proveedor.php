<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Proveedor extends Model
{
    protected $table = 'proveedores';

    protected $fillable = [
        'razon_social', 'ruc', 'email', 'telefono', 'direccion',
        'contacto', 'tipo', 'condicion_pago', 'estado',
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];

    public function materiasPrimas(): HasMany
    {
        return $this->hasMany(MateriaPrima::class);
    }
}
