<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Like;

class LikesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $like = new Like;
        $like->user_id = 1;
        $like->post_id = 1;
        $like->save();

        $like = new Like;
        $like->user_id = 2;
        $like->post_id = 2;
        $like->save();
    }
}
