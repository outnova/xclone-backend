<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Post;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $post = new Post();
        $post->content = 'This is a other post!';
        $post->is_published = true;
        $post->visibility = 'public';
        $post->user_id = User::find(3)->id;
        $post->save();
        //
    }
}
