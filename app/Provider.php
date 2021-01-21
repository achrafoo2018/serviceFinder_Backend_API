<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    protected $primaryKey = 'provider_id';
    protected $fillable = [
        'provider_id',
        'service',
        'speciality',
        'description',
        'rating'
    ];
}
