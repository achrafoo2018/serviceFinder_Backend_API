<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Provider;
use App\Client;
use Auth;
use JWTAuth;

class RememberMeController extends Controller
{
    public function checkRememberMeToken(Request $request){
        if (Auth::viaRemember()) {
            
        }
    }
}
