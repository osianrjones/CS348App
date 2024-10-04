<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Comment;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $comment = new Comment;
        $comment->comment = "What a cute cat!";
        $comment->user_id = 1;
        $comment->save();

        $comment = new Comment;
        $comment->comment = "What breed of dog is that?";
        $comment->user_id = 2;
        $comment->save();

        Comment::factory()->count(25)->create();
    }
}
