<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Evita errores de longitud de índice en versiones antiguas de MySQL.
        Schema::defaultStringLength(191);
    }
}
