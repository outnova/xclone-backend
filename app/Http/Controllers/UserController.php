<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function checkUsername(Request $request)
    {
        $username = $request->input('name'); // Obtiene el parámetro del cuerpo
        $exists = User::where('name', $username)->exists();
    
        return response()->json(['username' => $username, 'exists' => $exists]);
    }
    //
}
