<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Nette\Utils\Random;

class PostTagTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $tagCount=Tag::count();

       Post::all()->each(function ($post) use($tagCount){

        $take=random_int(1,$tagCount);

        $tagsIds=Tag::inRandomOrder()->take($take)->get()->pluck('id');//get the tags in random order but take just a $take tags and just the ids

        $post->tags()->sync($tagsIds);

       });


    }
}
