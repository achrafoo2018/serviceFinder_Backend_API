<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Provider;
use App\Client;
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
    public function register(Request $request){
        try{
            $user = User::create([
                'first_name'=> $request['first_name'],
                'last_name'=> $request['last_name'],
                'username' => $request['username'],
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
                'type' => $request['type']
            ]); 
            if($request['type'] == "client"){
                Client::create([
                    'client_id' => $user->id
                ]);
            }
            else if ($request['type'] == "provider"){
                Provider::create([
                    "provider_id" => $user->id
                ]);
            }
            return $this->login($request);
        }
        catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => $e
            ]);
        }

    }
}
