<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends  Seeder
{
  /**
   * Seed the application's database.
   *
   * @return void
   */
  public function run()
  {
    // \App\Models\User::factory(10)->create();
    // $users=User::factory(10)->create();
    // the $users is a collection so it have a methodes 
    // $posts=Post::factory(12)->make()->each(function($post) use ($users) {
    //      $post->user_id=$users->random()->id;//so i till him generate in the  a random user but i want to get the id so to add in the data base we use 
    //      $post->save();
    // });
    // Comment::factory(30)->make()->each(function ($comment) use ($posts){
    //     $comment->post_id=$posts->random()->id;
    //     $comment->save();
    // });
    if ($this->command->confirm('do you want to refresh the database ?')) {
      $this->command->call("migrate:refresh");
      $this->command->info('database was refreshed !');
    }

    $this->call([
      UsersTableSeeder::class,
      PostsTableSeeder::class,
      // CommentsTableSeeder::class,
      TagTableSeeder::class,
      PostTagTableSeeder::class
    ]);

  }
}
