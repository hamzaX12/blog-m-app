<?php

namespace App\Http\Controllers;

use App\Events\CommentPosted as MyCommentPosted;
use App\Http\Requests\StoreComment;
use App\Http\Resources\CommentResource;
use App\Jobs\NotifyUsersPostWasCommented;
use App\Mail\CommentedPostMarkdown;
use App\Mail\CommentPosted;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class PostCommentController extends Controller
{

    public function __construct()
    {

        $this->middleware('auth')->only(['store']);
    }

    public function show(Post $post)
    {

        return CommentResource::collection($post->comments()->with('user')->get());
    }

    public function store(StoreComment $request, Post $post)
    {

        $comment = $post->comments()->create([
            // when you create a object you can recuper it by assign it to a variable
            'content' => $request->content,
            'user_id' => $request->user()->id
        ]);

        event(new MyCommentPosted($comment));

        return redirect()->back();
    }

}
