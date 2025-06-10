<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // Campos que pueden ser asignados masivamente
    protected $fillable = [
        'url',
        'imageable_id',
        'imageable_type',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    // Una foto puede pertenecer a diferentes modelos (Restaurante, Reseña, etc.).
    // Relación: Polimórfica (MorphTo).
    // 'imageable' es el nombre del método en el modelo al que pertenece la imagen (ej. Restaurant::photos()).
    public function imageable()
    {
        return $this->morphTo();
    }
}
