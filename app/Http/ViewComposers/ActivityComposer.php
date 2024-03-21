<?php

namespace App\Http\ViewComposers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class ActivityComposer  {

    public function compose(View $view){
        $mostComments=Cache::remember('mostComments',now()->addMinute(10),function (){
            return Post::MostCommented()->take(5)->get();
        });
        $UsersMostPostWriting=Cache::remember('UsersMostPostWriting',now()->addMinute(10),function (){
            return User::UsersMostPostWriting()->take(5)->get();
        });
        $UsersActiveInLastMonth=Cache::remember('UsersActiveInLastMonth',now()->addMinute(10),function (){
            return User::userActiveInLastMonth()->take(5)->get();
        });

        $view->with([
            'mostComments'=>$mostComments,'UsersMostPostWriting'=>$UsersMostPostWriting,'UsersActiveInLastMonth'=>$UsersActiveInLastMonth
        ]);
    }

}