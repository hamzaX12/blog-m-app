<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use App\Models\Post;
use App\Models\User;

class hamza extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // $mostComments = Cache::remember('mostComments', now()->addSecond(30), function () {
        //     // $mostComments = Cache::remember('mostComments', now()->addMinute(1), function () {
        //     return Post::MostCommented()->take(5)->get();
        // });

        // $UsersMostPostWriting = Cache::remember('UsersMostPostWriting', now()->addSecond(30), function () {
        //     return User::UsersMostPostWriting()->take(5)->get();
        // });

        // $UsersActiveInLastMonth = Cache::remember('UsersActiveInLastMonth', now()->addSecond(30), function () {
        //     return User::userActiveInLastMonth()->take(5)->get();
        // });
        return Post::MostCommented()->take(5)->get();
    }
}
