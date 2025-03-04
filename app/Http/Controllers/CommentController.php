<?php

namespace App\Http\Controllers;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use App\Notifications\CommentNotification;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CommentController extends Controller
{
    /**
     * A function used to delete a comment from a post.
     */
    public function deleteComment(Comment $comment, Request $requet) {

         // Ensure the authorized user owns the comment.
        if (auth()->user()->name !== $comment->user->name && !(auth()-> user() -> isAdmin)) {
            abort(403, 'Unauthorized delete.');
        }

        $comment->delete();

        return redirect()->back()->with('success', 'Comment deleted successfully.');
    }

    /**
     * A create function for a user posting a new comment on a post.
     */
    public function createComment(Request $request, $postId) {

        //Find the post to comment on
        $post = Post::findOrFail($postId);

        //Create a new comment in the database
        $comment = $post->comments()->create([
            'user_id' => $request->user()->id,
            'comment' => $request->comment,
            'post_id' => $postId,
        ]);

        // Format the 'created_at' value to match the feed view
        $created_at = Carbon::parse($comment->created_at)->format('Y-m-d H:i:s');

        $post->user->notify(new CommentNotification($request->user()->name));

        //Return back to the AJAX call.
        return response()->json([
            'success' => true,
            'comment' => $comment,
            'user' => $comment->user,
            'created_at' => $created_at,
        ]);

    }

    public function markAllAsRead() {
        Auth::user()->unreadNotifications->markAsRead();
        return redirect()->back();
    }
}
