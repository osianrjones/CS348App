<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    //Check authentication
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }

    return view('auth.login');
});

Route::get('/dashboard', [ProfileController::class, 'getPosts'])
->middleware(['auth', 'verified'])
->name('dashboard');

Route::get('/feed', [PostController::class, 'getFeed'])
->middleware(['auth', 'verified'])
->name('feed');

//Route for a user deleting one of their own posts.
Route::delete('/posts/{post}', [ProfileController::class, 'deletePost'])->name('posts.deletePost');

//Route for a user to delete a comment on one of their posts.
Route::delete('/comments/{comment}', [CommentController::class, 'deleteComment'])->middleware(['auth', 'verified'])->name('comments.deleteComment');

//Route for a user to comment on a post
Route::post('/posts/{post}/comments', [CommentController::class, 'createComment'])->middleware(['auth', 'verified'])->name('comments.create');

Route::get('/user/{name}', [UserController::class, 'getUser'])->name('users.getUser');

//Route for creating a post
Route::post('/user/post', [PostController::class, 'createPost'])->name('posts.createPost');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
