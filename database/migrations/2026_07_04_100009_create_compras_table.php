<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('compras', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->foreignId('proveedor_id')->constrained('proveedores')->restrictOnDelete();
            $table->date('fecha');
            $table->enum('estado', ['recibida', 'pendiente', 'anulada'])->default('pendiente');
            $table->boolean('stock_aplicado')->default(false);
            $table->decimal('total', 12, 2)->default(0);
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });

        Schema::create('compra_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('compra_id')->constrained('compras')->cascadeOnDelete();
            $table->foreignId('materia_prima_id')->constrained('materia_primas')->restrictOnDelete();
            $table->decimal('cantidad', 10, 2)->default(1);
            $table->decimal('costo_unitario', 10, 2)->default(0);
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('compra_items');
        Schema::dropIfExists('compras');
    }
};
