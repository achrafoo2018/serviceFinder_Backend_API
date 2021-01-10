<?php

namespace App;
use App\Post;
use App\User;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{

    protected $fillable = [
        'user_id',
        'provider_id',
        'comment',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

}
