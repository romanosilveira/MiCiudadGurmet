<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // Campos que pueden ser asignados masivamente
    protected $fillable = [
        'user_id',
        'restaurant_id',
    ];

    // Indicar a Eloquent que esta tabla no usa un ID autoincremental
    public $incrementing = false;

    // Indicar a Eloquent que la clave primaria es compuesta
    protected $primaryKey = ['user_id', 'restaurant_id'];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    // Un registro de favorito pertenece a un usuario.
    // Relación: Uno a muchos (One-to-Many, la parte "belongsTo").
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Un registro de favorito pertenece a un restaurante.
    // Relación: Uno a muchos (One-to-Many, la parte "belongsTo").
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
