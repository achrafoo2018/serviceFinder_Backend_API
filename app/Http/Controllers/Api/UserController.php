<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;


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
            $user = User::find((int)$request->id);
            
            if($user == Auth::user()){

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
            else
                return response()->json([
                    'error' => 'User not logged in!'
                ]);
            
        }catch(ModelNotFoundException $e){
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }

    }

    public function updateAccount(Request $request){
        try{
            $user = User::find((int)$request->id);
            if($user == Auth::user()){

                if($user->first_name != $request->first_name)
                    $user->first_name = $request->first_name;
                
                if($user->last_name != $request->last_name)
                    $user->last_name = $request->last_name;
                    
                if($user->email != $request->email)
                    $user->email = $request->email;

                if($user->profile_picture != $request->profile_picture)
                    $user->profile_picture = $request->profile_picture;

                $user->save();
                
                return response()->json([
                    'success' => true,
                    'user' => $user
                ]);
            }
            else
                return response()->json([
                    'error' => 'User not logged in!'
                ]);
        }catch(ModelNotFoundException $e){
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }

    }

    public function changePassword(Request $request){

        try{
            $user = User::find((int)$request->id);
            if($user == Auth::user()){
                
                if($user->password != $request->password)
                    $user->password = $request->password;
                $user->save();
            
            return response()->json([
                'success' => true,
                'user' => $user
            ]);
            }
            else
                return response()->json([
                    'error' => 'User not logged in!'
                ]);
        }catch(ModelNotFoundException $e){
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }

    }
}
