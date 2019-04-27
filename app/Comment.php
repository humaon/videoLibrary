<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable=['user_id','video_id','comment'];
    public function users()
    {
        return $this->hasOne(User::class,'id','user_id')->select('id','name','photo_url','email');
    }
}
