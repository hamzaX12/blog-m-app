<?php

namespace App\Http\Controllers;

use App\Models\Tag;

class PostTagController extends Controller
{
    public function index($id)
    {

        return view('posts.index', [
            'posts' => Tag::postsWithUserAndCommentAndTags()->where('id', $id)->first()->posts,
        ]);
    }
}
