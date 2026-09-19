<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empleados', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('dni')->nullable();
            $table->string('cargo')->nullable();
            $table->enum('area', ['Corte', 'Confección', 'Acabado', 'Control de Calidad', 'Administración', 'Almacén'])->default('Confección');
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->date('fecha_ingreso')->nullable();
            $table->decimal('salario', 10, 2)->default(0);
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empleados');
    }
};
