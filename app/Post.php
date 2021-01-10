<?php

namespace App;

use App\Comment;
use App\PostComment;
use App\User;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public function user(){
        return $this->belongsTo(User::class);
    }

}
