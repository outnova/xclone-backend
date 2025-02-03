<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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

    public function getFollowersCount($userId) 
    {
        try {
            $user = User::findOrFail($userId);
            $followerCount = $user->followers()->count();

            return response()->json([
                'user_id' => $user->id,
                'follower_count' => $followerCount,
            ], 200);
        } catch(\Throwable $th) {
            return response()->json([
                'error_code' => 'server_error',
                'message' => 'Error al obtener la cantidad de seguidores',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function getFollowingCount($userId) 
    {
        try {
            $user = User::findOrFail($userId);
            $followingCount = $user->following()->count();

            return response()->json([
                'user_id' => $user->id,
                'following_count' => $followingCount,
            ], 200);
        } catch(\Throwable $th) {
            return response()->json([
                'error_code' => 'server_error',
                'message' => 'Error al obtener la cantidad de seguidos',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function getFollowers($userId)
    {
        try {
            $user = User::findOrFail($userId);
            $followers = $user->followers()->get();

            return response()->json([
                'user_id' => $user->id,
                'followers' => $followers,
            ], 200);
        } catch(\Throwable $th) {
            return response()->json([
                'error_code' => 'server_error',
                'message' => 'Error al obtener los seguidores',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function getFollowing($userId)
    {
        try {
            $user = User::findOrFail($userId);
            $following = $user->following()->get();

            return response()->json([
                'user_id' => $user->id,
                'following' => $following,
            ], 200);
        } catch(\Throwable $th) {
            return response()->json([
                'error_code' => 'server_error',
                'message' => 'Error al obtener los seguidores',
                'error' => $th->getMessage(),
            ], 500);
        }
    }
    //
}
