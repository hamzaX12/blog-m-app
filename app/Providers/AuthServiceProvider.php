<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        'App\Models\Post' => 'App\Policies\PostPolicy',
        'App\Models\User' => 'App\Policies\UserPolicy'
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Gate::resource('post','App\Policies\PostPolicy');

        // Gate::define('post.update','App\Policies\PostPolicy@update');
        // Gate::define('post.delete','App\Policies\PostPolicy@delete');

        // Gate::define('post.update',function ($user,$post){
        //     return $user->id === $post->user_id;
        // });

        // Gate::define('post.delete',function ($user,$post){
        //     return $user->id === $post->user_id;
        // });
        
        // // if this code return true the brevious code not execute
        Gate::before(function ($user,$ability){
            if ($user->is_admin && in_array($ability,['update','restore'])) {
                return true;
            }
        });

        Gate::define('secret.page',function ($user){
            return $user->is_admin;
        });

    }
}
