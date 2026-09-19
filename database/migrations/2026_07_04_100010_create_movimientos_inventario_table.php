<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movimientos_inventario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('materia_prima_id')->constrained('materia_primas')->cascadeOnDelete();
            $table->enum('tipo', ['entrada', 'salida', 'ajuste'])->default('entrada');
            $table->decimal('cantidad', 10, 2)->default(0);
            $table->decimal('stock_anterior', 10, 2)->default(0);
            $table->decimal('stock_resultante', 10, 2)->default(0);
            $table->string('motivo')->nullable();
            $table->string('referencia')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('fecha');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimientos_inventario');
    }
};
