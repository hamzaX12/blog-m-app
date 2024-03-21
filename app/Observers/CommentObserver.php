<?php

namespace App\Observers;

use App\Models\Comment;
use Illuminate\Support\Facades\Cache;

class CommentObserver
{
    /**
     * Handle the Comment "created" event.
     *
     * @param  \App\Models\Comment  $comment
     * @return void
     */
    public function creating(Comment $comment)
    {
        if (Cache::has("post-show-{$comment->commentable->id}")) {
            Cache::forget("post-show-{$comment->commentable->id}");
        }
    }
}
