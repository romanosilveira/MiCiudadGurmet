<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User; // Importa el modelo User
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // Para hashear contraseñas
use Illuminate\Support\Facades\Auth; // Para el intento de login
use Illuminate\Support\Facades\Validator; // Para validación

class AuthController extends Controller
{
    /**
     * Handle user registration.
     */
    public function register(Request $request)
    {
        // 1. Validar los datos de la petición (nombre, email, contraseña, confirmación)
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users', // Email debe ser único en la tabla users
            'password' => 'required|string|min:8|confirmed', // 'confirmed' busca 'password_confirmation'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        // 2. Crear el registro del usuario
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password), // Hashear la contraseña antes de guardar
            ]);

            // Crear un token personal para el nuevo usuario
            // Asegúrate de que el modelo User use el trait HasApiTokens
            $token = $user->createToken('auth_token')->plainTextToken;

            // 3. Devolver la respuesta JSON con el usuario y el token
            return response()->json([
                'success' => true,
                'message' => 'Usuario registrado exitosamente.',
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar el usuario.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle user login.
     */
    public function login(Request $request)
    {
        // 1. Validar las credenciales (email y contraseña)
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        // 2. Intentar autenticar al usuario
        // Auth::attempt verifica las credenciales y las compara con la base de datos
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => false,
                'message' => 'Credenciales inválidas.'
            ], 401); // Código 401 Unauthorized
        }

        // 3. Si la autenticación es exitosa, obtener el usuario autenticado
        $user = Auth::user();

        // 4. Crear un nuevo token personal para el usuario
        // Asegúrate de que el modelo User use el trait HasApiTokens
        $token = $user->createToken('auth_token')->plainTextToken;

        // 5. Devolver la respuesta JSON con el usuario y el token
        return response()->json([
            'success' => true,
            'message' => 'Inicio de sesión exitoso.',
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * Handle user logout (revoke current token).
     */
    public function logout(Request $request)
    {
        // Revocar el token actual del usuario autenticado
        // Esto hace que el token sea inválido inmediatamente
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sesión cerrada exitosamente.'
        ]);
    }
}<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User; // Importa el modelo User
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // Para hashear contraseñas
use Illuminate\Support\Facades\Auth; // Para el intento de login
use Illuminate\Support\Facades\Validator; // Para validación

class AuthController extends Controller
{
    /**
     * Handle user registration.
     */
    public function register(Request $request)
    {
        // 1. Validar los datos de la petición (nombre, email, contraseña, confirmación)
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users', // Email debe ser único en la tabla users
            'password' => 'required|string|min:8|confirmed', // 'confirmed' busca 'password_confirmation'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        // 2. Crear el registro del usuario
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password), // Hashear la contraseña antes de guardar
            ]);

            // Crear un token personal para el nuevo usuario
            // Asegúrate de que el modelo User use el trait HasApiTokens
            $token = $user->createToken('auth_token')->plainTextToken;

            // 3. Devolver la respuesta JSON con el usuario y el token
            return response()->json([
                'success' => true,
                'message' => 'Usuario registrado exitosamente.',
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar el usuario.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle user login.
     */
    public function login(Request $request)
    {
        // 1. Validar las credenciales (email y contraseña)
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        // 2. Intentar autenticar al usuario
        // Auth::attempt verifica las credenciales y las compara con la base de datos
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => false,
                'message' => 'Credenciales inválidas.'
            ], 401); // Código 401 Unauthorized
        }

        // 3. Si la autenticación es exitosa, obtener el usuario autenticado
        $user = Auth::user();

        // 4. Crear un nuevo token personal para el usuario
        // Asegúrate de que el modelo User use el trait HasApiTokens
        $token = $user->createToken('auth_token')->plainTextToken;

        // 5. Devolver la respuesta JSON con el usuario y el token
        return response()->json([
            'success' => true,
            'message' => 'Inicio de sesión exitoso.',
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * Handle user logout (revoke current token).
     */
    public function logout(Request $request)
    {
        // Revocar el token actual del usuario autenticado
        // Esto hace que el token sea inválido inmediatamente
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sesión cerrada exitosamente.'
        ]);
    }
}
