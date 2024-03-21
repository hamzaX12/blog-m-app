<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreComment;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class PostCommentController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:sanctum');
    // }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Post $post, Request $request)
    {
        $perPage = $request->input('per_page') ?? null;
        return CommentResource::collection($post->comments()->with('user')
            ->paginate($perPage)->appends([
                'per_page' => $perPage,
                'sir_fhalk' => 'la_had_bladna'
            ]));
        // add in the url of the paginate a variable per_page
        // like this http://127.0.0.1:8000/api/v1/posts/30/comments?page=1
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Post $post, StoreComment $request)
    {
        $comment = $post->comments()->create([
            // when you create a object you can recuper it by assign it to a variable
            'content' => $request->content,
            'user_id' => $request->user()->id
        ]);

        return  new CommentResource($comment);
    }
    public function storeAPI(StoreComment $request, Post $post)
    {
        // return response()->json([
        //     'messge' => 'to postman i love you so mush',
        // ]);
        $post->comments()->create([
            'content' => $request->content,
            'user_id' => $request->user_id
        ]);

        return response()->json([
            'messge' => 'to postman i love you so mush',
        ]);

    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post, Comment $comment)
    {

        return new CommentResource($comment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Post $post, Comment $comment, StoreComment $request)
    {
        $comment->content = $request->content;
        $comment->save();
        return new CommentResource($comment);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post, Comment $comment)
    {
        $comment->delete();
        return response()->noContent();
    }
}
