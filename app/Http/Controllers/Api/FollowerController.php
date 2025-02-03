<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class FollowerController extends Controller
{
    public function follow(Request $request, $followedId)
    {
        try {
            $user = $request->user();

            //validate if following user

            if($user->following()->where('followed_id', $followedId)->exists()) {
                return response()->json([
                    'message' => 'Ya estás siguiendo a este usuario',
                ], 400);
            }

            //validate if user to follow exists
            $followedUser = User::find($followedId);
            if(!$followedUser) {
                return response()->json([
                    'message' => 'El usuario que intentas seguir no existe',
                ], 400);
            }

            if($followedId == $user->id) {
                return response()->json([
                    'message' => 'No puedes seguirte a ti mismo',
                ], 400);
            }

            //create follow relationship
            $user->following()->attach($followedId);

            return response()->json([
                'message' => 'Ahora sigues a este usuario',
            ], 201);
        }
        catch(\Throwable $th) {
            return response()->json([
                'error_code' => 'server_error',
                'message' => 'Ocurrió un error al intentar seguir al usuario',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function unfollow(Request $request, $followedId) {
        try {
            $user = $request->user();

            //validate if following user

            if(!$user->following()->where('followed_id', $followedId)->exists()) {
                return response()->json([
                    'message' => 'No estás siguiendo a este usuario',
                ], 400);
            }

            if($followedId == $user->id) {
                return response()->json([
                    'message' => 'No puedes dejar de seguirte a ti mismo',
                ], 400);
            }

            //delete the relationship

            $user->following()->detach($followedId);

            return response()->json([
                'message' => 'Has dejado de seguir a este usuario',
            ], 200);
        }
        catch(\Throwable $th) {
            return response()->json([
                'error_code' => 'server_error',
                'message' => 'Ocurrió un error al intentar de seguir a este usuario',
                'error' => $th->getMessage(),
            ], 500);
        }
    }
    //
}
