<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [ProfileController::class, 'getPosts'])
->middleware(['auth', 'verified'])
->name('dashboard');

//Route for a user deleting one of their own posts.
Route::delete('/posts/{post}', [ProfileController::class, 'deletePost'])->name('posts.deletePost');

//Route for a user to delete a comment on one of their posts.
Route::delete('/comments/{comment}', [CommentController::class, 'deleteComment'])->middleware(['auth', 'verified'])->name('comments.deleteComment');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
