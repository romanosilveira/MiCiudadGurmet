<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // Asegúrate de que este trait esté aquí

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // Campos que pueden ser asignados masivamente (name, email, password son comunes)
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // Laravel 10+ gestiona el hash aquí
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    // Un usuario puede tener muchos restaurantes (creados por él).
    // Relación: Uno a muchos (One-to-Many). Un usuario tiene muchos restaurantes, un restaurante pertenece a un usuario.
    // Laravel infiere la clave foránea 'user_id' en la tabla 'restaurants'.
    public function restaurants()
    {
        return $this->hasMany(Restaurant::class);
    }

    // Un usuario puede tener muchas reseñas.
    // Relación: Uno a muchos (One-to-Many). Un usuario tiene muchas reseñas, una reseña pertenece a un usuario.
    // Laravel infiere la clave foránea 'user_id' en la tabla 'reviews'.
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Un usuario puede tener muchos restaurantes favoritos.
    // Relación: Muchos a muchos (Many-to-Many). Un usuario puede tener muchos favoritos, un restaurante puede ser favorito de muchos usuarios.
    // Laravel infiere la tabla pivote 'favorite_restaurant' por convención, pero especificamos 'favorites' explícitamente.
    public function favorites()
    {
        // return $this->belongsToMany(Restaurant::class, 'favorites', 'user_id', 'restaurant_id'); // Más explícito
        return $this->belongsToMany(Restaurant::class, 'favorites'); // Laravel puede inferir las claves si siguen la convención
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    // Scope de ejemplo para filtrar usuarios activos (si tuvieras una columna 'is_active')
    // Beneficio: Reutiliza consultas complejas o comunes de manera legible.
    // public function scopeActive($query)
    // {
    //     return $query->where('is_active', true);
    // }

    /*
    |--------------------------------------------------------------------------
    | Accesores
    |--------------------------------------------------------------------------
    */

    // Accesor de ejemplo para capitalizar el nombre del usuario al recuperarlo
    // Beneficio: Formatea atributos automáticamente al ser accedidos, sin alterar el valor original en DB.
    // public function getNameAttribute($value)
    // {
    //     return ucwords($value);
    // }
}
