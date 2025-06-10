<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Restaurant; // Importa el modelo Restaurant
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Importa el facade Auth
use Illuminate\Support\Facades\Validator; // Para validación manual si no usas FormRequest

class RestaurantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener todos los restaurantes con sus relaciones (reviews y photos para optimización)
        // Optimización de consultas: Usamos with() para evitar el problema de "N+1 queries"
        // Esto carga las reseñas y fotos relacionadas con cada restaurante en una sola consulta.
        $restaurants = Restaurant::with(['reviews', 'photos', 'user'])->get();

        // Devolver los restaurantes como respuesta JSON
        return response()->json($restaurants);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Estructura de store():
        // 1. Validar los datos de la petición.
        //    Puedes usar un Form Request para una validación más limpia (php artisan make:request StoreRestaurantRequest)
        //    Pero para el ejemplo, usaremos Validator directamente aquí.
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            // 'user_id' no es requerido aquí si lo asignas automáticamente
            // o si solo el usuario autenticado puede crear un restaurante.
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422); // Código 422 Unprocessable Entity
        }

        // 2. Crear el registro del restaurante en la base de datos.
        // Asegúrate de que el user_id se asigne automáticamente desde el usuario autenticado.
        // Esto evita que un usuario intente crear un restaurante a nombre de otro.
        try {
            $restaurant = Restaurant::create([
                'name' => $request->name,
                'address' => $request->address,
                'phone' => $request->phone,
                'user_id' => Auth::id(), // Asigna el ID del usuario autenticado
            ]);

            // 3. Devolver la respuesta JSON con el restaurante creado y un código 201 (Created).
            return response()->json([
                'success' => true,
                'message' => 'Restaurante creado exitosamente.',
                'data' => $restaurant
            ], 201);
        } catch (\Exception $e) {
            // Manejo de errores: Si algo sale mal durante la creación (ej. error de base de datos)
            return response()->json([
                'success' => false,
                'message' => 'No se pudo crear el restaurante.',
                'error' => $e->getMessage()
            ], 500); // Código 500 Internal Server Error
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Restaurant $restaurant)
    {
        // Manejo de errores: Si el modelo no se encuentra, Laravel automáticamente lanza 404
        // gracias a la inyección de modelo implícita (Restaurant $restaurant).
        // Optimización de consultas: Carga las relaciones cuando se muestra un restaurante específico.
        $restaurant->load(['reviews', 'photos', 'user']);
        return response()->json($restaurant);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Restaurant $restaurant)
    {
        // Manejo de permisos: Asegurarse de que solo el propietario o un admin pueda actualizar.
        // Ejemplo de autorización simple:
        if ($request->user()->id !== $restaurant->user_id) {
            // Abortar con 403 Forbidden si el usuario no es el propietario
            abort(403, 'No tienes permiso para actualizar este restaurante.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255', // 'sometimes' para que sea opcional en la actualización
            'address' => 'sometimes|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $restaurant->update($request->all());
            return response()->json([
                'success' => true,
                'message' => 'Restaurante actualizado exitosamente.',
                'data' => $restaurant
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo actualizar el restaurante.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Restaurant $restaurant)
    {
        // Manejo de permisos: Asegurarse de que solo el propietario o un admin pueda eliminar.
        if ($request->user()->id !== $restaurant->user_id) {
            abort(403, 'No tienes permiso para eliminar este restaurante.');
        }

        try {
            $restaurant->delete();
            return response()->json([
                'success' => true,
                'message' => 'Restaurante eliminado exitosamente.'
            ], 204); // Código 204 No Content para eliminación exitosa
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo eliminar el restaurante.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
