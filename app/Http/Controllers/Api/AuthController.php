<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
class AuthController extends Controller
{
    public function login(Request $request){
        $creds = $request->only(['email', 'password']);
        if(!$token=auth()->attempt($creds)){
            return response()->json([
                'success'=>false
            ]);
        }
        return response()->json([
            'success'=>true,
            'token' => $token,
            'user' => Auth::user()
        ]);
    }
}
