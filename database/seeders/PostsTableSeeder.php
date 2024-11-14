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
        //Statically create two posts for admin users.
        $post = new Post;
        $post->image_path = asset("images/108706.JPG");
        $post->user_id = 1;
        $post->location='Swansea';
        $post->save();

        $post = new Post;
        $post->image_path = asset("images/142266.JPG");
        $post->user_id = 2;
        $post->location='Swansea';
        $post->save();

        //Create 10 random posts for admin user 1
        Post::factory()->count(10)->create(['user_id' => 1]);

        //Create 10 random posts for admin user 2
        Post::factory()->count(10)->create(['user_id' => 2]);

        //Create 50 posts for random users
        Post::factory()->count(50)->create();

    }
}
