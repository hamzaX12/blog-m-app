<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $num=(int)$this->command->ask('how many of user you want generate ?',10);
        User::factory($num)->create();
    }
}
