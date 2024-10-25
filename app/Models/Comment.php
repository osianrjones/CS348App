<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    /**
     * Retrieve the post this comment belongs to.
     * 
     * @return One-To-One relationship between one comment on one post.
     */
    public function post() {
        return $this -> belongsTo(Post::class);
    }

    /**
     * Retrieve the user who commented this comment.
     * 
     * @return One-To-One relationship between one comment from one user.
     */
    public function user() {
        return $this -> belongsTo(User::class);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'comment',
        'post_id'
    ];
}
