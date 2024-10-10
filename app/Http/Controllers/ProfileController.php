<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Post;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * A function used to get all the logged in users posts.
     */
    public function getPosts(Request $request) {
        $user = $request -> user();
        
        $posts = $user->posts()->paginate(3);

        $data = [
            'user'=> $request -> user(),
            'posts'=>$posts,
        ];

        return view('dashboard', $data);
    }

    /**
     * Function used to delete a post from a users profile.
     */
    public function deletePost(Post $post) {

        \Log::info('Attempting delete', ['post_id' => $post->id]);

        $post->delete();

        \Log::info('Post deleted successfully', ['post_id' => $post->id]);

        return redirect()->back()->with('success', 'Post deleted successfully.');
    }
}
