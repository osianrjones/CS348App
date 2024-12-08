<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\Tag;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or find tags
        $tag1 = Tag::firstOrCreate(['name' => 'Admin']);
        $tag2 = Tag::firstOrCreate(['name' => '2024']);

        //Statically create two posts for admin users.
        $post = new Post;
        $post->image_path = asset("images/108706.JPG");
        $post->user_id = 1;
        $post->location='Swansea';
        $post->save();

        // Attach tags to the post
        $post->tags()->attach([$tag1->id, $tag2->id]);

        $post = new Post;
        $post->image_path = asset("images/142266.JPG");
        $post->user_id = 2;
        $post->location='Swansea';
        $post->save();

        // Attach tags to the post
        $post->tags()->attach([$tag1->id, $tag2->id]);

        //Create 10 random posts for admin user 1
        Post::factory()->count(10)->create(['user_id' => 1]);

        //Create 10 random posts for admin user 2
        Post::factory()->count(10)->create(['user_id' => 2]);

        //Create 50 posts for random users
        Post::factory()->count(50)->create();

    }
}
