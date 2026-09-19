<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ordenes_produccion', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->foreignId('pedido_id')->nullable()->constrained('pedidos')->nullOnDelete();
            $table->foreignId('producto_id')->constrained('productos')->restrictOnDelete();
            $table->foreignId('empleado_id')->nullable()->constrained('empleados')->nullOnDelete();
            $table->integer('cantidad')->default(1);
            $table->enum('etapa', ['corte', 'confeccion', 'acabado', 'control', 'terminado'])->default('corte');
            $table->unsignedTinyInteger('avance')->default(0); // 0-100
            $table->enum('estado', ['en_proceso', 'pausado', 'terminado'])->default('en_proceso');
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ordenes_produccion');
    }
};
