<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CommentRequest $request)
    {
        //
        $request->validated();

        $user = auth()->user();

        $post = Post::find($request->post_id);

        if ($post) {
            $comment = Comment::create([
                "user_id" => $user->id,
                "post_id" => $request->post_id,
                "comment" => $request->comment,
            ]);

            $comment->load("user", "post");

            $reponse = [
                "message" => "Comment stored succefully",
                "data" => $comment,
            ];

            return response($reponse, 201);
        }

        return response([
            "message" => "Post not found"
        ], 404);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $comment = Comment::find($id);

        if ($comment) {
            $comment->load("user", "post");

            $reponse = [
                "message" => "comment retrieved succesfully",
                "data" => $comment
            ];

            return response($reponse, 200);
        } else {
            return response([
                "message" => "Comment not found"
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request, string $id)
    {
        //
        $request->validated();

        $user = auth()->user();

        $comment = Comment::find($id);

        if ($comment) {
            if ($user->id === $comment->user_id) {
                $comment->update([
                    "user_id" => $user->id,
                    "post_id" => $request->post_id,
                    "comment" => $request->comment,
                ]);
    
                $comment->load("user", "post");
    
                $reponse = [
                    "message" => "Comment updated succefully",
                    "data" => $comment,
                ];
    
                return response($reponse, 201);
            }else {
                return response(["message" => "Not Authorized"], 404);
            }

        } else {
            return response([
                "message" => "Comment not found"
            ], 404);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

         $comment = Comment::find($id);

        if ($comment) {

            $comment->delete();

            $reponse = [
                "message" => "Comment deleted succefully",
            ];

            return response($reponse, 200);
        } else {
            return response([
                "message" => "Comment not found"
            ], 404);
        }
    }
}
