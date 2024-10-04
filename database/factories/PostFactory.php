<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Comment;
use App\Models\Like;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
             'image_path' => "images/" . Str::random(10) . ".jpg",
             'user_id' => fake()->randomElement(User::pluck('id')->toArray()),
             'comment_id' => fake()->randomElement(Comment::pluck('id')->toArray()),
             'like_id' => fake()->randomElement(Like::pluck('id')->toArray()),
        ];
    }
}
