<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommentsController extends Controller
{
    public function create(Request $request){
        $comment = new Comment;
        $comment->user_id = Auth::user()->id;
        $comment->post_id = $request->id;
        $comment->comment = $request->comment;
        $comment->save();

        return reponse()->json([
            'success'=>true,
            'message'=>'comment added'
        ]);
    }

    public function update(Request $request){
        $comment = Comment::find($request->id);
        //check if user is editing his own comment
        if ($comment->id != Auth::user()->id){
            return reponse()->json([
                'succes' => false,
                'messages' => 'unauthorize access'
            ]);
        }
        $comment->comment = $request->comment;
        $comment->update();
        return reponse()->json([
            'succes' => true,
            'messages' => 'comment edited'
        ]);
    }

    public function delete(Request $request){
        $comment = Comment::find($request->id);
        //check if user is editing his own comment
        if ($comment->id != Auth::user()->id){
            return reponse()->json([
                'succes' => false,
                'messages' => 'unauthorize access'
            ]);
        }
        $comment->delete();

        return reponse()->json([
            'succes' => true,
            'messages' => 'comment deleted'
        ]);
    }

    public function comments(Request $request){
        $comment = Comment::where('post_id',$request->id)->get();
        //show user of each comment
        foreach($comments as $comment){
            $comment->user;
        }
        return reponse()->json([
            'succes' => true,
            'comments' => $comments
        ]);
    }


}
