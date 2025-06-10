<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tabla para clasificar los restaurantes por categorías (ej. "Italiana", "China").
        Schema::create('categories', function (Blueprint $table) {
            $table->id(); // id: clave primaria autoincremental

            // name: cadena de texto para el nombre de la categoría, debe ser único y obligatorio
            $table->string('name')->unique();

            $table->timestamps(); // created_at, updated_at: marcas de tiempo automáticas
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
