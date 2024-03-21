<?php

namespace App\Mail;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CommentPosted extends Mailable
{
    use Queueable, SerializesModels;

    public $comment;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Comment $comment)
    {
        $this->comment=$comment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
        $subject="Comment Post for ".$this->comment->commentable->title;
        return $this
        // ->attachFromStorage($this->comment->commentable->title,'profile_picture.jpeg')
        // he point on the disk by default in your app in this exemple we have the app/public
        ->attachFromStorageDisk('public',$this->comment->commentable->title,'profile_picture.jpeg')
        ->subject($subject)
        ->view('emails.posts.comment');
    }
}