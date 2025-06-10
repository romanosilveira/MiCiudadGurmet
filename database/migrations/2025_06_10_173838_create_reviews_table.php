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
        // Tabla de reseñas para que los usuarios valoren restaurantes.
        Schema::create('reviews', function (Blueprint $table) {
            $table->id(); // id: clave primaria autoincremental

            // rating: entero entre 1 y 5, obligatorio
            $table->integer('rating');

            // comment: texto para el comentario de la reseña, opcional (nullable)
            $table->text('comment')->nullable();

            // user_id: clave foránea que referencia la tabla 'users', id del usuario que hizo la reseña
            // foreign: user_id -> users.id, cascade on delete (si se elimina el usuario, se eliminan sus reseñas)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // restaurant_id: clave foránea que referencia la tabla 'restaurants', id del restaurante valorado
            // foreign: restaurant_id -> restaurants.id, cascade on delete (si se elimina el restaurante, se eliminan sus reseñas)
            $table->foreignId('restaurant_id')->constrained()->onDelete('cascade');

            $table->timestamps(); // created_at, updated_at: marcas de tiempo automáticas
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
