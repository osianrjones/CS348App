<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;

class UserController extends Controller
{
    /**
     * A function used to return a user profile.
     */
    public function getUser($userName) {
        //Find the user or throw 404
        $user = User::where('name', $userName)->firstOrFail();

        //Number of posts per page.
        $perPage = 3;

        $posts = Post::where('user_id', $user->id)
                    ->with('user') //Eager load the user for access in blade.
                    ->orderBy('created_at', 'desc')
                    ->paginate($perPage);

        $postCount = Post::where('user_id', $user->id)->count();

        return view('user', compact('posts', 'postCount', 'user'));
    }
}
