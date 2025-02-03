<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
//use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
//use Laravel\Passport\PersonalAccessTokenResult;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $messages = [
                'name.required' => 'El nombre es obligatorio.',
                'name.unique' => 'El nombre ya está en uso.',
                'email.required' => 'El correo electrónico es obligatorio.',
                'email.unique' => 'El correo electrónico ya está en uso.',
                'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            ];

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255|unique:users',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
            ], $messages);

            if($validator->fails()) {
                $data = [
                    'error_code' => 'validation_error',
                    'message' => 'Error en la validación de datos',
                    'errors' => $validator->errors(),
                    'status' => 400
                ];
                return response()->json($data, 400);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Registro exitoso',
                'user' => $user,
                'token' => $user->createToken('auth_token')->plainTextToken,
            ], 200);
        }
        catch(\Throwable $th) {
            return response()->json([
                'error_code' => 'server_error',
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }

        /*
        if(!$user) {
            $data = [
                'message' => 'Error al crear el usuario',
                'status' => 500
            ];
            return response()->json($data, 500);
        }

        $data = [
            'user' => $user,
            'status' => 201
        ];
        */

        //return response()->json($data, 201);
        //$this->info('The command was successful!');

        //return "Hola mundo";
        /*
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

    
        return response()->json(['message' => 'Registration successful']);
        */
        //return response()->json(['user' => $user], 201);
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'identifier' => 'required|string',
                'password' => 'required|string',
            ]);

            $user = User::where('email', $request->identifier)
                ->orWhere('name', $request->identifier)
                ->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'error_code' => 'invalid_login',
                    'message' => 'Credenciales incorrectas',
                    'status' => 401,
                ], 401);
            }

            return response()->json([
                'status' => true,
                'message' => 'Inicio de sesión exitoso',
                'token' => $user->createToken('auth_token')->plainTextToken,
            ], 200);
        }
        catch(\Throwable $th) {
                return response()->json([
                    'error_code' => 'server_error',
                    'status' => false,
                    'message' => $th->getMessage(),
                ], 500);
        }
        /*
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Invalid credentials',
                'status' => 401,
            ], 401);
        }

        $user = Auth::user();
        $token = $request->user()->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Inicio de sesión exitoso',
            'user'=> $user,
            'token' => $token,
            'status' => 200,
        ], 200);
        */

        //return response()->json(['access_token' => $token, 'token_type' => 'Bearer']);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión finalizada con éxito',
            'data' => $request->user()
        ], 200);
    }

    public function user(Request $request) {
        return response()->json([
            'message' => 'Información consultada',
            'data' => $request->user()
        ], 200);
    }
    //
}
