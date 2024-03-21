<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public const LOCALES = [
        'en' => 'English',
        'ar' => 'Arabic',
        'fr' => 'French'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];



    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function posts()
    {
        return $this->hasMany(Post::class);
    }


    public function comments()
    {

        return $this->morphMany(Comment::class, 'commentable')->Latest();
    }

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }


    /*************************************** */
    //       scope
    public function scopeUsersMostPostWriting(Builder $query)
    {
        return $query->withCount('posts')->orderBy('posts_count', 'desc');
    }

    public function scopeUserActiveInLastMonth(Builder $query)
    {

        return $query->withCount(['posts' => function (Builder $query) {
            $query->whereBetween(static::CREATED_AT, [now()->subMonth(1), now()]);
        }])
            ->having('posts_count', '>', 3)
            ->orderBy('posts_count', 'desc');

        // return $query->with('posts',function ($post){
        //     $post->where('created_at','<=',30);
        // })->get();
    }
}
