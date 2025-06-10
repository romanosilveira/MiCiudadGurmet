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
        // Tabla pivote para la relación muchos a muchos entre Usuarios y Restaurantes favoritos.
        Schema::create('favorites', function (Blueprint $table) {
            // user_id: clave foránea que referencia la tabla 'users', id del usuario
            // foreign: user_id -> users.id, cascade on delete
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // restaurant_id: clave foránea que referencia la tabla 'restaurants', id del restaurante
            // foreign: restaurant_id -> restaurants.id, cascade on delete
            $table->foreignId('restaurant_id')->constrained()->onDelete('cascade');

            // Ambas claves foráneas compuestas forman la clave primaria de esta tabla pivote.
            $table->primary(['user_id', 'restaurant_id']);

            $table->timestamps(); // created_at, updated_at: marcas de tiempo automáticas
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};
