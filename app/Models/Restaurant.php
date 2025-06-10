<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // Campos que pueden ser asignados masivamente
    protected $fillable = [
        'name',
        'address',
        'phone',
        'user_id', // Clave foránea que también se puede asignar masivamente
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    // Un restaurante pertenece a un usuario (el que lo creó).
    // Relación: Uno a muchos (One-to-Many, la parte "belongsTo"). Un restaurante pertenece a un usuario.
    // Laravel infiere la clave foránea 'user_id' en la tabla 'restaurants'.
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Un restaurante puede tener muchas reseñas.
    // Relación: Uno a muchos (One-to-Many). Un restaurante tiene muchas reseñas, una reseña pertenece a un restaurante.
    // Laravel infiere la clave foránea 'restaurant_id' en la tabla 'reviews'.
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Un restaurante puede pertenecer a muchas categorías.
    // Relación: Muchos a muchos (Many-to-Many). Un restaurante puede tener muchas categorías, una categoría puede tener muchos restaurantes.
    // Laravel infiere la tabla pivote 'category_restaurant' por convención.
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    // Un restaurante puede tener muchas fotos (relación polimórfica).
    // Relación: Uno a muchos polimórfica (MorphMany).
    // 'imageable' es el nombre del método en el modelo 'Photo' que define la relación polimórfica.
    public function photos()
    {
        return $this->morphMany(Photo::class, 'imageable');
    }

    // Un restaurante puede ser favorito de muchos usuarios.
    // Relación: Muchos a muchos (Many-to-Many) inversa de la relación 'favorites' en el modelo User.
    // Laravel infiere la tabla pivote 'favorites'.
    public function favoritedByUsers()
    {
        // return $this->belongsToMany(User::class, 'favorites', 'restaurant_id', 'user_id'); // Más explícito
        return $this->belongsToMany(User::class, 'favorites'); // Laravel puede inferir las claves si siguen la convención
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    // Scope para filtrar restaurantes por nombre (búsqueda parcial)
    // Beneficio: Permite construir consultas reutilizables y legibles.
    public function scopeSearch($query, $name)
    {
        return $query->where('name', 'like', '%' . $name . '%');
    }

    /*
    |--------------------------------------------------------------------------
    | Accesores
    |--------------------------------------------------------------------------
    */

    // Accesor para formatear el número de teléfono
    // Beneficio: Permite modificar el valor de un atributo cuando se accede a él.
    // public function getFormattedPhoneAttribute()
    // {
    //     // Asume que el teléfono está guardado como 123456789
    //     $phone = $this->attributes['phone'];
    //     if ($phone) {
    //         return '(' . substr($phone, 0, 3) . ') ' . substr($phone, 3, 3) . '-' . substr($phone, 6);
    //     }
    //     return null;
    // }
}
