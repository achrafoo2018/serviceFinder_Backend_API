<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use JWTAuth;
use Auth;
use Illuminate\Auth\SessionGuard;

class UserController extends Controller
{
    public function displayUser(Request $request){
        try{

            $user = User::find((int)$request['id']);
            if($user->type == 'provider'){
                
                $provider = Provider::where('provider_id',(int)$user->id)->get();

                return response()->json([
                    'success'=>true,
                    'user' => $user,
                    'provider' => $provider
                ]);
            }
            else
                return response()->json([
                    'success'=>true,
                    'user' => $user,
                ]);
        }
        catch(ModelNotFoundException $e){
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }

    }

    public function updateProfile(Request $request){
        try{
            $user = $this->getUserById($request);
            if($user){

                if($this->validateToken($request, $user)){

                $provider = Provider::where('provider_id',(int)$user->id)->get();
                if($provider->service != $request->service)
                    $provider->service = $request->service;
                
                if($provider->speciality != $request->speciality)
                    $provider->speciality = $request->speciality;
                    
                if($provider->phone_number != $request->phone_number)
                    $provider->phone_number = $request->phone_number;

                if($provider->description != $request->description)
                    $provider->description = $request->description;

                $provider->save();

                return response()->json([
                    'success' => true,
                    'user' => $provider
                ]);
            }
            return $this->vaidateTokenError();
        }
        return $this->getUserByIdError();

            
        }catch(ModelNotFoundException $e){
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }

    }

    public function updateAccount(Request $request){
        try{
            $user = $this->getUserById($request);
            if($user){

                if($this->validateToken($request, $user)){

                    if($user->first_name != $request->first_name && $request->first_name != "")
                        $user->first_name = $request->first_name;
                    
                    if($user->last_name != $request->last_name && $request->last_name != "")
                        $user->last_name = $request->last_name;
                        
                    if($user->email != $request->email && $request->email != "")
                        $user->email = $request->email;

                    if($user->profile_picture != $request->profile_picture && $request->profile_picture != "")
                        $user->profile_picture = $request->profile_picture;

                    $user->save();
                    
                    return response()->json([
                        'success' => true,
                        'user' => $user
                    ]);
                }
                return $this->vaidateTokenError();
            }
            return $this->getUserByIdError();

        }catch(ModelNotFoundException $e){
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }

    }

    public function changePassword(Request $request)
    {
        try{
            $user = $this->getUserById($request);
            if($user){

                if($this->validateToken($request, $user)){

                    $password = $request->password;

                    $user->password = Hash::make($password);
            
                    $user->save();

                    return response()->json([
                        'Message' => "Password changed successfully!",
                        'token' => $user->remember_token,
                        'user' => $user
                    ]);
                }
                return $this->vaidateTokenError();
            }
            return $this->getUserByIdError();
        }catch(ModelNotFoundException $e){
            return response()->json([
                'error' => $e.getMessage()
            ]);
        }
    }

    public static function validateToken(Request $request, User $user){
        if($user->remember_token == $request->token)
            return true;
        return false;

    }
    
    public static function getUserById(Request $request){
        return User::find((int)$request->id);
    }

    public static function getUserByIdError(){
        return response()->json([
            'error' => 'Incorrect User!'
        ]);
    }

    public static function validateTokenError(){
        return response()->json([
            'error' => 'Incorrect Token!'
        ]);
    }
}
