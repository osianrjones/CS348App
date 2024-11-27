<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;

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
            'image' => 'required|image|mimes:jpeg,png,jpg',
            'location' =>'required'
        ]);

        if ($request->file('image')) {
            //The user creating the post
            $userId = $request -> user() -> id;

            $imagePath = $request->file('image')->store('images', 'public');


            $post = new Post;
            $post->image_path = asset('storage/' . $imagePath);
            $post->location = $request->location;
            $post->user_id = $userId;
            $post->save();

            return redirect()->route('dashboard')->with('message', 'Your post was created successfully.');
        } else {

            return redirect()->route('dashboard');
        } 
    }

    function getPlaceName($latitude, $longitude)
    {
        $apiKey = config('services.opencage.api_key');
        $url = "https://api.opencagedata.com/geocode/v1/json?q={$latitude}+{$longitude}&key={$apiKey}";

        $response = Http::get($url);

        if ($response->successful() && !empty($response->json()['results'])) {
            return $response->json()['results'][0]['formatted'];
        }

        return 'Unknown Location';
    }

    function getAdminSearch(Request $request) {
        if ($request->user() && $request -> user() -> isAdmin) {

            $location = $request->query('location');
            $user = $request->query('user');
            $postDateTime = $request->query('post');

            $query = Post::query();

            if ($location) {
                $query -> where('location', $location);
            }

           /*  if ($user) {
                $userId = User::where('name', '=', $user)->value('id');

                if ($userId) {
                    $query -> where('user_id', $userId);
                }
            } */

            $query -> where('user_id', $user);

            if ($postDateTime) {
                $query -> where('created_at', $postDateTime);
            }

            $perPage = 3;

            $posts = $query->orderBy('created_at', 'desc')->paginate($perPage);

            $users = User::all();

            $postDatesTimes = Post::distinct('created_at')->pluck('created_at');

            $locations = Post::distinct('location')->pluck('location');

            $posts->appends(request()->except('page'));

            return view('admin', compact('posts','locations','users','postDatesTimes'));
        } else {
            return redirect()->route('dashboard');
        }
    }

    /**
     * Function used to delete a post from a users profile.
     */
    public function adminDeletePost(Post $post, Request $request) {
        if ($request->user() && $request -> user() -> isAdmin) {

        $post->delete();

        return redirect()->back()->with('success', 'Post deleted successfully.');
        } else {
            return redirect()->route('dashboard');
        }
    }

    public function bulkDelete(Request $request)
    {
        $postIds = $request->input('post_ids', []);

        dd($postIds);

        if (!empty($postIds)) {
            Post::whereIn('id', $postIds)->delete();

            return redirect()->back()->with('success', 'Selected posts were deleted.');
        }

        return redirect()->back()->with('error', 'No posts selected.');
        }

}
