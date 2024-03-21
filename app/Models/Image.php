<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    use HasFactory;

    protected $fillable=['path'];

    // public function post(){
    //     return $this->belongsTo(Post::class);
    // }
    // to but the name should be the prefixe of the of the foreignId
    public function imageable(){
        return $this->morphTo();
    }
    public function url(){
        return Storage::url($this->path);
    }




}