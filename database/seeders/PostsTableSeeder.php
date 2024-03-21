<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        $users=User::all();
        if ($users->count()==0) {
            $this->command->info('the users if empty');
            return ;
        }
        $pNum=(int)$this->command->ask('how many of post you want generate ?',30);
        Post::factory($pNum)->make()->each(function($post) use ($users) {
            $post->user_id=$users->random()->id;//so i till him generate in the  a random user but i want to get the id so to add in the data base we use
            $post->save();
       });
    }
}
