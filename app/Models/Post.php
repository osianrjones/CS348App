<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Comment;

class Post extends Model
{
    use HasFactory;

    /**
     * Retrieve the user who posted this item.
     * 
     * @return One-To-One relationship between the one user who owns this one post.
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * Retrieve the comments commented on this post.
     * 
     * @return One-To-Many relationship between many comments commented on this one post.
     */
    public function comments() {
        return $this->hasMany(Comment::class);
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
