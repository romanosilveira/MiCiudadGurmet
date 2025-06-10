<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // Campos que pueden ser asignados masivamente
    protected $fillable = [
        'rating',
        'comment',
        'user_id',
        'restaurant_id',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    // Una reseña pertenece a un usuario.
    // Relación: Uno a muchos (One-to-Many, la parte "belongsTo").
    // Laravel infiere la clave foránea 'user_id' en la tabla 'reviews'.
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Una reseña pertenece a un restaurante.
    // Relación: Uno a muchos (One-to-Many, la parte "belongsTo").
    // Laravel infiere la clave foránea 'restaurant_id' en la tabla 'reviews'.
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    // Scope para filtrar reseñas por calificación mínima
    // Beneficio: Ayuda a obtener reseñas de alta calidad fácilmente.
    public function scopeMinRating($query, $minRating)
    {
        return $query->where('rating', '>=', $minRating);
    }
}
