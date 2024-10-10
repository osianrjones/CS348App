<?php

namespace App\Http\Controllers;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * A function used to delete a comment from a post.
     */
    public function deleteComment(Comment $comment) {

         // Ensure the authorized user owns the post this comment belongs to.
        if (auth()->user()->id !== $comment->post->user_id) {
            abort(403, 'Unauthorized delete.');
        }

        $comment->delete();

        return redirect()->back()->with('success', 'Comment deleted successfully.');
    }
}
