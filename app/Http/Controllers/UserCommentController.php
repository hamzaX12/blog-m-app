<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreComment;
use App\Models\User;

class UserCommentController extends Controller
{

    public function __construct(){

        $this->middleware('auth')->only(['store']);

    }
    public function store(StoreComment $request,User $user){

        // insert a comment to a user so he go to the commentable_type=App\Models\User
        // an put the id of the user to the
        $user->comments()->create([
            'content' => $request->content,
            // so the $request->user() is the personne Auth want to comment to the $user
            'user_id' => $request->user()->id
        ]);

        return redirect()->back()->with('status','comment was created');
    }
    public function storeAPI(StoreComment $request,User $user){

        // insert a comment to a user so he go to the commentable_type=App\Models\User
        // an put the id of the user to the
        $user->comments()->create([
            'content' => $request->content,
            // so the $request->user() is the personne Auth want to comment to the $user
            'user_id' => $request->user()->id
        ]);

        return redirect()->back()->with('status','comment was created');
    }
}
