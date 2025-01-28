<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
                'files.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,mov,avi,wmv|max:10240', //opcional
                'visibility' => 'in:public,private,followers',
            ]);

            $user = $request->user(); //get the user
            
            //create the post
            $post = Post::create([
                'content' => $validated['content'],
                //'media' => $validated['media'] ?? null,
                'visibility' => $validated['visibility'] ?? 'public',
                'user_id' => $user->id,
            ]);

            $uploadedFiles = [];

            //updoad files (images - videos)
            if($request->hasFile('files')) {
                foreach($request->file('files') as $file) {

                    //get the extesion of the file
                    $extension = $file->getClientOriginalExtension();

                    $fileType = DB::table('files_types')
                        ->where('extension', $extension)
                        ->first();

                    if(!$fileType) {
                        //if the extension isn't registered, throw a error
                        return response()->json([
                            'message' => "El tipo de archivo con extensión .$extension no está soportado."
                        ], 400);
                    }

                    //generate a unique name for each file 
                    $fileName = uniqid(time()) . '_' . '.' . $file->getClientOriginalExtension();

                    //define the folder by file type (image or video)
                    $folder = $fileType->type === 'image' ? 'images' : 'videos';

                    $path = $file->storeAs("public/posts/{$post->id}/{$folder}", $fileName);

                    //save the file in db
                    $fileRecord = $post->files()->create([
                        'path' => str_replace('public/', 'storage/', $path),
                        'id_type' => $fileType->id,
                        'id_post' => $post->id,
                    ]);

                    $uploadedFiles[] = $fileRecord;
                }
            }

            return response()->json([
                'message' => 'Post creado exitosamente',
                'data' => $post,
                'files' => $uploadedFiles,
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Ocurrió un error durante la creación del post",
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id) {
        try {
            $validated = $request->validate([
                'content' => 'nullable|string|max:1000',
                'visibility' => 'nullable|in:public,private,followers',
            ]);

            $post = Post::findOrFail($id);
            $user = $request->user(); //get the user

            if($post->user_id !== $user->id) { //check if the user has autorization to edit the post
                return response()->json(['message' => 'No autorizado para actualizar este post'], 403);
            }
            
            //edit the post
            $post->update($validated);

            return response()->json([
                'message' => 'Post editado exitosamente',
                'data' => $post,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Ocurrió un error editando el post",
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function delete(Request $request, $id) {
        try {
            $post = Post::findOrFail($id);
            $user = $request->user(); //get the user

            if($post->user_id !== $user->id) { //check if the user has autorization to edit the post
                return response()->json(['message' => 'No autorizado para eliminar este post'], 403);
            }

            //delete the files associated to post (if exists)
            $files = $post->files; //we use the "posts-files" relation 

            foreach($files as $file) {
                //delete the file from storage system
                $filePath = storage_path('app/public/' . str_replace('storage/', '', $file->path));

                if(file_exists($filePath)) {
                    unlink($filePath);
                }

                $file->forceDelete();
            }
            $post->forceDelete();

            // delete post folder if it's empty (optinal)
            $folderPath = storage_path("app/public/posts/{$post->id}");
            if (is_dir($folderPath) && count(scandir($folderPath)) == 2) { 
                // Verifica que la carpeta esté vacía (solo contiene '.' y '..')
                rmdir($folderPath); // Eliminar la carpeta si está vacía
            }

            return response()->json([
                'message' => 'Post eliminado exitosamente',
                'data' => $post,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Ocurrió un error eliminando el post",
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function show($id) {
        try {
            $post = Post::findOrFail($id);

            return response()->json([
                'message' => 'Post encontrado exitosamente',
                'data' => $post,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Ocurrió un error al obtener el post",
                'error' => $th->getMessage(),
            ], 500);
        }
    }
    //
}
