<?php

namespace App\Models;

use App\Scopes\AdminShowDeleteScope;
use App\Scopes\LatestScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Post extends Model
{

    use HasFactory;

    use SoftDeletes;

    protected $fillable = ['title', 'content', 'slug', 'active', 'user_id'];
    protected $hidden = ['created_at', 'deleted_at'];
    // we use the $fillable to mention to the create  || $post=Post::create($data);
    // the attrebuit that he will prendre on consdiration

    // relationships


    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->Latest();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    // public function tags(){
    //     return $this->belongsToMany(Tag::class)->withTimestamps();
    // }
    // to
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable')->withTimestamps();
    }

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }
    // scopes

    public function scopeMostCommented(Builder $query)
    {
        return $query->withCount('comments')->orderBy('comments_count', 'desc');
    }
    public function scopePostWithUserAndCommentAndTagsAndImage(Builder $query)
    {
        return $query->withCount('comments')->with(['user', 'tags', 'image']);
    }
    // this a way to delete the posts and his comments but there is the delete cascase and the update cascade

    public static function boot()
    {

        static::addGlobalScope(new AdminShowDeleteScope);
        static::creating(function (Post $post) {
            if (Cache::has("UsersMostPostWriting")) {
                Cache::forget("UsersMostPostWriting");
            }
            if (Cache::has("posts")) {
                Cache::forget("posts");
            }
        });
        parent::boot();
        static::addGlobalScope(new LatestScope);
    }
}
