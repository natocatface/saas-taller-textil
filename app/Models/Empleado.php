<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $table = 'empleados';

    protected $fillable = [
        'nombre', 'dni', 'cargo', 'area', 'telefono',
        'email', 'fecha_ingreso', 'salario', 'estado',
    ];

    protected $casts = [
        'fecha_ingreso' => 'date',
        'salario' => 'decimal:2',
        'estado' => 'boolean',
    ];
}
