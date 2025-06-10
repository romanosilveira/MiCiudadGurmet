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
        // Tabla para almacenar información sobre los restaurantes.
        Schema::create('restaurants', function (Blueprint $table) {
            $table->id(); // id: clave primaria autoincremental

            // name: cadena de texto para el nombre del restaurante, obligatorio
            $table->string('name');

            // address: cadena de texto para la dirección del restaurante, obligatorio
            $table->string('address');

            // phone: cadena de texto para el número de teléfono, opcional (nullable)
            $table->string('phone')->nullable();

            // user_id: clave foránea que referencia la tabla 'users', id del usuario que creó el restaurante
            // foreign: user_id -> users.id, cascade on delete (si se elimina el usuario, se eliminan sus restaurantes)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->timestamps(); // created_at, updated_at: marcas de tiempo automáticas
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurants');
    }
};
