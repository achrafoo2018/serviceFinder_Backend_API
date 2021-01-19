<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Provider;
use App\Comment;
use App\PostComment;
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
            $user = User::where('remember_token', ($request->bearerToken()))->first();
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
            return $this->validateTokenError();
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
            $user = User::where('remember_token', ($request->bearerToken()))->first();
            if($user){

                if($this->validateToken($request, $user)){

                    if($user->first_name != $request->first_name && $request->first_name != "")
                        $user->first_name = $request->first_name;

                    if($user->last_name != $request->last_name && $request->last_name != "")
                        $user->last_name = $request->last_name;

                    if($request->exists("email") && $user->email != $request->email && $request->email != "")
                        $user->email = $request->email;

                    if($user->profile_picture != $request->profile_picture && $request->profile_picture != ""){
                        file_put_contents('storage/profile/'.time().'.jpg', \base64_decode($request->profile_picture));
                        $user->profile_picture = 'storage/profile/'.time().'.jpg';
                    }
                    $user->save();

                    return response()->json([
                        'success' => true,
                        'user' => $user
                    ]);
                }
                return $this->validateTokenError();
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
            $user = User::where('remember_token', ($request->bearerToken()))->first();
            if($user){

                if($this->validateToken($request, $user)){

                    if(Hash::check($request->current_password, $user->password)){

                    $password = $request->password;


                    $user->password = Hash::make($password);

                    $user->save();

                    return response()->json([
                        'success' => true,
                        'message' => "Password changed successfully!",
                        'user' => $user
                    ]);

                    }
                    else{
                    return response()->json([
                        'success' => false,
                        'error' => "Password incorrect!",
                        'code' => 502
                    ]);
                    }
                }
                return $this->validateTokenError();
            }
            return $this->getUserByIdError();
        }catch(ModelNotFoundException $e){
            return response()->json([
                'error' => $e.getMessage()
            ]);
        }
    }



    public function getCommentsOnUserProfile(Request $request){
        try{
            $user = User::where("email", $request->bearerToken())->first();
            if($user){
                    $comments = Comment::where('provider_id',$user->id)->orderBy('created_at', 'desc')->get();
                    foreach($comments as $comment)
                        $comment->user;
                    return response()->json([
                        'success' => true,
                        'comments' => $comments,
                        'user' => $user,
                    ]);

            }
            return response()->json([
                'success' => false,
                'error' => 'Incorrect User!'
            ]);
        }catch(ModelNotFoundException $e){
            return response()->json([
                'error' => $e.getMessage()
            ]);
        }
    }


    public function createComment(Request $request){
        try {
            $x = 0;
            if(count(Comment::where('user_id',$request->user_id)->where('provider_id',$request->provider_id)->get()) > 0){
                $x = 1;
                $c = Comment::where('user_id',$request->user_id)->where('provider_id',$request->provider_id)->get();
                $user = User::find($request->user_id);
                return response()->json([
                    'error' => 'Profile already reviewed',
                    'counter' => $x,
                    'comment' => $c,
                    'user' => $user

                ]);
            }
            $comment = new Comment($request->all());
            $comment->save();
            return response()->json([
                'success' => true,
                'comments' => $comment,
                'counter' => $x
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => $e.getMessage()
            ]);
        } catch (Exception $e){
            return response()->json([
                'error' => $e.getMessage()
            ]);
        }
    }

    public function editComment(Request $request){
        try {

            $comment = Comment::find($request->comment_id);
            $comment->comment = $request->comment;
            $comment->save();
            return response()->json([
                'success' => true,
                'comments' => $comment,
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => $e.getMessage()
            ]);
        } catch (Exception $e){
            return response()->json([
                'error' => $e.getMessage()
            ]);
        }
    }

    public function deleteComment(Request $request){
        try {

            $comment = Comment::find($request->bearerToken());
            if($comment){
                $comment->delete();
                return response()->json([
                    'success' => true,
                    'comment' => $comment,
                ]);
            }
            else{
                return response()->json([
                    'error' => "Could not find comment ".$request->bearerToken()."!",
                ]);
            }

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => $e.getMessage()
            ]);
        } catch (Exception $e){
            return response()->json([
                'error' => $e.getMessage()
            ]);
        }
    }

    public function deletePostComment(Request $request){
        try {
            $comment = PostComment::find($request->bearerToken());
            if($comment){
                $comment->delete();
                return response()->json([
                    'success' => true,
                    'comment' => $comment,
                ]);
            }
            else{
                return response()->json([
                    'error' => $request->bearerToken(),
                ]);
            }

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => $e.getMessage()
            ]);
        } catch (Exception $e){
            return response()->json([
                'error' => $e.getMessage()
            ]);
        }
    }


    public static function validateToken(Request $request, User $user){
        if($user->remember_token == $request->bearerToken())
            return true;
        return false;

    }

    public static function getUserById(Request $request){
        return User::find((int)$request->id);
    }

    public static function getUserByIdError(){
        return response()->json([
            'success' => false,
            'error' => 'Incorrect User!'
        ]);
    }

    public static function validateTokenError(){
        return response()->json([
            'success' => false,
            'error' => 'Incorrect Token!'
        ]);
    }

}
