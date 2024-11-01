<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
     /**
     * A function used to get all posts from the database that do not belong to a certain user.
     */
    public function getFeed(Request $request) {
        //The user posts we don't want to see 
        $excludedUserId = $request -> user() -> id;

        //Number of posts per page.
        $perPage = 3;

        $posts = Post::where('user_id', '!=', $excludedUserId)
                    ->orderBy('created_at', 'desc')
                    ->paginate($perPage);

        return view('feed', compact('posts'));
    }

    /**
     * Handle a new post from the user.
     */
    public function createPost(Request $request) {
        //Validate the image is correct format

        $request -> validate([
            'image' => 'required|image|mimes:jpeg,png,jpg'
        ]);

        if ($request->file('image')) {
            //The user creating the post
            $userId = $request -> user() -> id;

            $imagePath = $request->file('image')->store('images', 'public');


            $post = new Post;
            $post->image_path = asset('storage/' . $imagePath);
            $post->user_id = $userId;
            $post->save();

            return redirect()->route('dashboard');
        } else {

            return redirect()->route('dashboard');
        } 
    }
}
