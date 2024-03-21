<?php

namespace App\Providers;

use App\Http\Resources\CommentResource;
use App\Http\ViewComposers\ActivityComposer;
use App\Models\Comment;
use App\Models\Post;
use App\Observers\CommentObserver;
use App\Observers\PostObserver;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // in this section we till that we want to inject all the data form 
        // the ActivityComposer::class directly to the  view by             
        view()->composer('posts.sidebar',ActivityComposer::class);
        // view()->composer('*',ActivityComposer::class);
        // in this case if you want to inject your code in all the views in you app 
        //use the '*'
        Post::observe(PostObserver::class);
        Comment::observe(CommentObserver::class);

        CommentResource::withoutWrapping();

 

    }
}