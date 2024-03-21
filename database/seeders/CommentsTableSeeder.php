<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $posts=Post::all();
        $user=User::all();
        if ($posts->count()==0) {
            $this->command->info('the users if empty');
            return ;
        }
        $cNum=(int)$this->command->ask('how many of comment you want generate ?',100);
        Comment::factory($cNum)->make()->each(function ($comment) use ($posts,$user){
            $comment->post_id=$posts->random()->id;
            $comment->user_id=$user->random()->id;
            $comment->save();
        });
    }
}
