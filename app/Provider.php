<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    protected $fillable = [
        'provider_id',
        'service',
        'speciality',
        'phone_number',
        'description',
        'rating'
    ];
}
