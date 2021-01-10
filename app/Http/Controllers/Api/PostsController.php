<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\UserController;
use App\Post;
use App\PostComment;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class PostsController extends Controller
{
    public function create(Request $request){

        try{

            $user = UserController::getUserById($request);
            if($user){
                if(UserController::validateToken($request, $user)){
                    $post = new Post;
                    $post->user_id = $request["id"];
                    $post->desc = $request["desc"];
                    $post->speciality = $request["speciality"];
                    //check if post has photo
                    if($request->photo != ''){
                        //choose a unique name for photo
                        $photo = 'storage/posts/'.time().'.jpg';
                        file_put_contents($photo,base64_decode($request->photo));
                        $post->post_image = $photo;
                    }
                    $post->save();
                    $post->user;
                    return response()->json([
                        'success' => true,
                        'message' => 'posted',
                        'post' => $post
                    ]);
                }
                return UserController::validateTokenError();

            }
            return UserController::getUserByIdError();

        }catch(ModelNotFoundException $e){
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function posts(){
        $posts = Post::orderBy('id','desc')->get();
        foreach($posts as $post){
            //get user of post
            $post->user;
            //comments count
        }
        return response()->json([
            'success' => true,
            'posts' => $posts
        ]);
    }

    public function myPosts(Request $request){

        try{
            $user = User::where('remember_token', ($request->bearerToken()))->first();
            if ($user){
                    $posts = $user->posts;
                    return response()->json([
                        'success' => true,
                        'posts' => $posts,
                        'user' => $user
                    ]);
            }
            return UserController::getUserByIdError();
        }catch(ModelNotFoundException $e){
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }

    }
    public function getCommentsOnPost(Request $request){
        try{
            $post = Post::find($request->bearerToken());
            if($post){
                    $postComments = PostComment::where("post_id", $post->id)->orderByDesc("created_at")->get();
                    foreach($postComments as $comment)
                        $comment->user;
                    return response()->json([
                        'success' => true,
                        'comments' => $postComments,
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
        try{
            $postComment = new PostComment($request->all());
            $postComment->save();
            return response()->json([
                'success' => true,
                'comment' => $postComment,
            ]);
        }catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => $e.getMessage()
            ]);
        }
    }
    public function getPost(Request $request){

        try{
            $post = Post::find($request->bearerToken());
            if ($post){
                $post->user;
                return response()->json([
                    'success' => true,
                    'post' => $post,
                ]);
            }
            return response()->json([
                'success' => false,
                'post' => $post,
            ]);
        }catch(ModelNotFoundException $e){
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }

    }
    public function getSpecialities(Request $request){
        try{
            $specialities = \DB::table("specialities")->get();
            return response()->json([
                'success' => true,
                'specialities' => $specialities
            ]);

        }catch(ModelNotFoundException $e){
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }
}
