<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $fillable=['user_id','video_id'];

    public function users()
    {
        return $this->hasOne(User::class,'id','user_id')->select('id','name','photoUrl','email');
    }

}
