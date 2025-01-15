<?php

namespace App\Http\Controllers\Api;

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
    public function create() {

    }

    public function update() {

    }

    public function delete() {
        
    }
    //
}
