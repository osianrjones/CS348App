<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Comment;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the Comment seeding.
     */
    public function run(): void
    {
        //Two static comments for admin users.
        $comment = new Comment;
        $comment->comment = "What a cute dog!";
        $comment->user_id = 1;
        $comment->post_id = 1;
        $comment->save();

        $comment = new Comment;
        $comment->comment = "What breed of dog is that?";
        $comment->user_id = 2;
        $comment->post_id = 2;
        $comment->save();

        //Factory generate 25 random comments, between random posts and random users.
        Comment::factory()->count(25)->create();
    }
}
