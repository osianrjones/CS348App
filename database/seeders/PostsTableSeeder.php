<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Post;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $post = new Post;
        $post->image_path = "images/dog.jpg";
        $post->user_id = 1;
        $post->save();

        $post = new Post;
        $post->image_path = "images/cat.jpg";
        $post->user_id = 2;
        $post->save();

        Post::factory()->count(10)->create();
    }
}
