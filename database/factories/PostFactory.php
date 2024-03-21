<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

// $factory->define(Post::class,function (Faker $faker){
//     $title=$this->faker->realText(50);
//         return [
//             'title'=>$title,
//             'slug'=>Str::slug($title,'-'),
//             'content'=>$faker->text,
//             'active'=>$faker->boolean,
//         ];
// });

class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title=$this->faker->realText(50);
        return [
            'title'=>$title,
            'slug'=>Str::slug($title,'-'),
            // 'slug'=>$this->faker->realText(50),
            'content'=>$this->faker->text(),
            // 'active'=>Str::random(1),
            'active'=>$this->faker->boolean(),
            // 'user_id'=>$this->faker->numberBetween(0,100),
            'updated_at'=> $this->faker->dateTimeBetween('-3 years')
        ];
    }
}
