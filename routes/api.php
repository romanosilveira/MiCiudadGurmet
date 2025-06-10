<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RestaurantController;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// --- Rutas Públicas (sin autenticación) ---

// Rutas de autenticación (Registro e Inicio de Sesión)
// POST /api/register - Para registrar un nuevo usuario
Route::post('/register', [AuthController::class, 'register']);
// POST /api/login - Para iniciar sesión y obtener un token
Route::post('/login', [AuthController::class, 'login']);

// Opcional: Si quieres que cualquiera pueda ver la lista de restaurantes sin autenticación
// GET /api/restaurants - Obtener todos los restaurantes (público)
// Route::get('restaurants', [RestaurantController::class, 'index']);
// Si dejas esta línea fuera, 'index' solo será accesible bajo autenticación (como en el apiResource de abajo).

// --- Rutas Protegidas (requieren un token de autenticación de Sanctum) ---

Route::middleware('auth:sanctum')->group(function () {
    // GET /api/user - Obtener el usuario autenticado (ya viene por defecto en Laravel)
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // POST /api/logout - Cerrar sesión (invalidar el token actual)
    Route::post('/logout', [AuthController::class, 'logout']);

    // Rutas RESTful para Restaurantes (CRUD)
    // El 'apiResource' crea automáticamente estas 5 rutas:
    // GET    /api/restaurants         -> RestaurantController@index
    // POST   /api/restaurants         -> RestaurantController@store
    // GET    /api/restaurants/{id}    -> RestaurantController@show
    // PUT    /api/restaurants/{id}    -> RestaurantController@update
    // DELETE /api/restaurants/{id}    -> RestaurantController@destroy
    Route::apiResource('restaurants', RestaurantController::class);

    // TODO: Añadir aquí apiResource para Categorías, Reseñas, Fotos si los necesitas,
    // y rutas para Favoritos si no es un apiResource completo.
    // Ejemplo:
    // Route::apiResource('categories', Api\CategoryController::class);
    // Route::apiResource('reviews', Api\ReviewController::class);
    // Route::apiResource('photos', Api\PhotoController::class);

    // Para Favoritos, que es una tabla pivote, quizás no uses un apiResource completo,
    // sino rutas personalizadas si quieres añadir/quitar un favorito:
    // Route::post('/favorites', [FavoriteController::class, 'store']); // Marcar como favorito
    // Route::delete('/favorites/{restaurant_id}', [FavoriteController::class, 'destroy']); // Desmarcar
});
