<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Provider;
use App\Client;
use Auth;
use JWTAuth;
use App\Http\Controllers\Api\UserController;

class AuthController extends Controller
{
    public function login(Request $request){
        $creds = $request->only(['email', 'password']);
        $token=JWTAuth::attempt($creds);
        
        if(!$token){
            return response()->json([
                'success'=>false,
                'message' => 'invalid credentials'
            ]);
        }
        $user = Auth::user();
        $user->remember_token = $token;
        $user->save();
        return response()->json([
            'success'=>true,
            'token' => $token,
            'user' => Auth::user()
        ]);
    }


    
    public function register(Request $request){
        $rules = [
            'type' => 'required',
            'email'    => 'unique:users|required',
            'password' => 'required',
        ];
    
        $input     = $request->only('email','password', "type");
        $validator = Validator::make($input, $rules);
    
        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->messages()]);
        }

        $user = User::create([
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'type' => $request['type']
        ]); 

        if(strtolower($request['type']) == "provider"){
            Provider::create([
                "provider_id" => $user->id
            ]);
        }
        
        else{
            Client::create([
                'client_id' => $user->id
            ]);
        }
            
        return $this->login($request);
        
    }
    public function logout(Request $request){
        try{
            
            $user = UserController::getUserById($request);
            if($user){
                if(UserController::validateToken($request, $user)){

                    $user->remember_token = "";
                    $user->save();

                    return response()->json([
                        'Message' => "User logged out successfully!",
                    ]);

                }
                return UserController::validateTokenError();
            }
            return UserController::getUserByIdError();
        }
        catch(Exception $e)
        {
            return response()->json([
                'message' => "".$e
            ]);
        }
            
        }
        
}
