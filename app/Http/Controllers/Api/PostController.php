<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    public function index() {
        $posts = Post::all();
        
        return response()->json([
            'message' => 'Respuesta de posts exitosa',
            'data' => $posts,
        ], 200);
    }
    public function create(Request $request) {
        try {
            $validated = $request->validate([
                'content' => 'required|string|max:1000',
                'media' => 'nullable|file|mimes:jpg,png,jpeg,gif|max:2048', //opcional
                'visibility' => 'in:public,private,followers',
            ]);

            $user = $request->user(); //get the user
            
            //create the post
            $post = Post::create([
                'content' => $validated['content'],
                'media' => $validated['media'] ?? null,
                'visibility' => $validated['visibility'] ?? 'public',
                'user_id' => $user->id,
            ]);

            return response()->json([
                'message' => 'Post creado exitosamente',
                'data' => $post,
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Ocurrió un error durante la creación del post",
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function update() {

    }

    public function delete() {
        
    }
    //
}
