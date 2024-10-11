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
        //Check if a user is passed into the post factory.
        $defaultUserId = User::first()->id ?? null;

        //Get a random file the post will have.
        $files = glob(public_path('images/*.*'));
        $key = array_rand($files);
        $file = $files[$key];

        // Convert the system path to a web-accessible path.
        $relativePath = str_replace(public_path(), '', $file);

        $image_path = asset($relativePath);

        //Assign the post to the user passed in, or if null find a random user.
        return [
             'image_path' => $image_path,
             'user_id' => fake()->randomElement(User::pluck('id')->toArray()) ?: $defaultUserId,
             'created_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }

}
