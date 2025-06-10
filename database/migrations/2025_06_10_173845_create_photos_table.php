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
        // Tabla para almacenar las URLs de las fotos. Soporta relaciones polimórficas.
        Schema::create('photos', function (Blueprint $table) {
            $table->id(); // id: clave primaria autoincremental

            // url: cadena de texto para la URL o ruta de la imagen, obligatorio
            $table->string('url');

            // imageable_id: id del modelo al que pertenece la imagen (ej. id de un restaurante o una reseña)
            // imageable_type: nombre de la clase del modelo al que pertenece (ej. 'App\Models\Restaurant')
            // Estos dos campos forman la clave polimórfica para la relación.
            $table->morphs('imageable');

            $table->timestamps(); // created_at, updated_at: marcas de tiempo automáticas
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('photos');
    }
};
