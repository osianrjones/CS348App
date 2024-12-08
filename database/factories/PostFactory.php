<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Tag;
use App\Models\Comment;
use App\Models\Like;
use Faker\Generator as Faker;

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
             'location' => fake()->city,
        ];
    }

    public function configure()
{
    return $this->afterCreating(function ($post) {
        // Fetch or create random tags
        $tags = Tag::inRandomOrder()->take(fake()->numberBetween(1, 5))->get();

        // If there are no tags, create a few
        if ($tags->isEmpty()) {
            $tags = Tag::factory()->count(5)->create();
        }

        // Attach the tags to the post
        $post->tags()->attach($tags->pluck('id')->toArray());
    });
}

}
