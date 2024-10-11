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
}
