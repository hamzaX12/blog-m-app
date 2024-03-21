<?php

use App\Http\Controllers\Api\V1\ApiAuthController;
use App\Http\Controllers\Api\V1\PostCommentController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Routing\RouteGroup;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\MockObject\Stub\ReturnArgument;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->name('api.v1.')->group(function () {
    Route::post('/login', [ApiAuthController::class, 'login']);
    Route::get('status', function () {
        return response()->json(['status' => 'ok']);
    })->name('atatus');
    Route::apiResource('posts.comments', PostCommentController::class);
    ////////////////////////////////////////////////////////////////
    Route::get('/posts', [PostController::class, 'indexAPI']);
    Route::get('/posts/{id}', [PostController::class, 'showAPI']);
    Route::post('/posts/create', [PostController::class, 'storeAPI']);


    // Route::post('/posts/{id}/comment', [PostCommentController::class, 'storeAPI']);
    Route::post('/posts/{id}/comment', [PostController::class, 'storeCommentAPI']);
    Route::get('/compose', [PostController::class, 'composeAPI']);
    Route::get('/chart/post', [PostController::class, 'postsChartApi']);
    Route::get('/chart/comment', [PostController::class, 'CommentChartApi']);
    Route::get('/chart/tagTime', [PostController::class, 'showPostTagTime']);
    Route::get('/chart/Time', [PostController::class, 'PostTime']);

    // Route::get('/posts/{id}', function ($id) {
    //     // return $id;
    //     return response()->json(['status' => $id]);
    // });

});

// the route in the api is allways prefixed by the api/
