<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\PostController;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index() {
        $postController = new PostController();
        return $postController->index();
    }
    //
}
