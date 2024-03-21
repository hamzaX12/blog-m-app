<?php

namespace App\Models;

use App\Scopes\LatestScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Comment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['content', 'user_id'];
    protected $hidden = ['deleted_at', 'created_at'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function commentable()
    {
        // reutrn to the personne with  commentable_id
        return $this->morphTo();
    }

    public function tags()
    {
        // taggable is the prefixe
        return $this->morphToMany(Tag::class, 'taggable')->withTimestamps();
    }


    // respect the nomination scopeLatest i mean scope name
    // so we will use just the local scope not the global scope
    public function scopeLatest(Builder $query)
    {
        return $query->orderBy(static::UPDATED_AT, 'desc');
    }

    // global scope
    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(new LatestScope);
        static::creating(function (Comment $comment) {
            // if (Cache::has("post-show-{$comment->commentable->id}")) {
            //     Cache::forget("post-show-{$comment->commentable->id}");
            // }
            if (Cache::has("mostComments")) {
                Cache::forget("mostComments");
            }
        });
        // }); if you want to  check this go to the CommentObserver.php

    }
}
