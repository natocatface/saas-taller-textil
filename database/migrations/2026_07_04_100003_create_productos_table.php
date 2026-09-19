<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->string('nombre');
            $table->string('categoria')->nullable();
            $table->string('talla')->nullable();
            $table->string('color')->nullable();
            $table->decimal('precio', 10, 2)->default(0);
            $table->decimal('costo', 10, 2)->default(0);
            $table->integer('stock')->default(0);
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
