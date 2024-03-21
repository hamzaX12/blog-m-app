<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostCommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostTagController;
use App\Http\Controllers\UserCommentController;
use App\Http\Controllers\UserController;
use App\Mail\CommentedPostMarkdown;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

/*
|--------------------------------------------------------------------------
| Web Routesisa
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/home', [HomeController::class, 'home'])->name('home');
Route::get('/secret', [HomeController::class, 'secret'])->name('secret')
    ->middleware('can:secret.page');;

Route::get('/posts/archive', [PostController::class, 'archive'])->name('archive');
Route::get('/posts/all', [PostController::class, 'all'])->name('all');
Route::patch('/posts/{id}/restore', [PostController::class, 'restore']);
Route::delete('/posts/{id}/forcedelete', [PostController::class, 'forcedelete']);
Route::resource('/posts', PostController::class);

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');

Route::get('/posts/tag/{id}', [PostTagController::class, 'index'])->name('post.tag.index');

Route::resource('posts.comments', PostCommentController::class)->only(['store', 'show']);
// Route::post('posts/{id}/comment', [PostCommentController::class, 'storeAPI']);
Route::resource('users.comments', UserCommentController::class)->only(['store']);


Route::get('/home', [HomeController::class, 'index'])->name('home');


Route::resource('users', UserController::class)->only(['show', 'update', 'edit']);


Route::get('/mailable', function () {

    $comment = Comment::find(1);

    return new CommentedPostMarkdown($comment);
});
