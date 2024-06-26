<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
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
    public function store(PostRequest $request)
    {
        //validated request
        $request->validated();

        //get current user
        $user = auth()->user();

        //create post
        $post = Post::create([
            "user_id" => $user->id,
            "title" => $request->title,
            "content" => $request->content
        ]);

        $response = [
            "message" => "Post created sucessfully",
            "data" => $post
        ];

        return response($response, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $post = Post::find($id);

        if ($post) {
            $postWithComments = Post::with("comments")->findOrFail($id);
            $response = [
                "message" => "Post retrieved succesfully",
                "data" => $postWithComments
            ];
            return response($response);
        } 
                 
        return response(["message" => "Post not found"], 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, string $id)
    {
        //
        $post = Post::find($id);

        $user = auth()->user();

        if ($post) {
            if ($user->id === $post->user_id) {
                $request->validated();
                $post->update($request->all());
                $post->load('comments');


                $response = [
                    "message" => "Post updated succesfully",
                    "data" => $post,
                    "current_user" => $user->id
                ];
                return response($response, 201);
            } else {
                return response(["message" => "Not Authorized"], 404);
            }
        } else {
            return response(["message" => "Post not found"], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::find($id);

        if ($post) {
            $post->delete();

            $response = [
                "message" => "Post deleted successfully",
            ];
            return response($response, 201);
        }

        return response(["message" => "Post not found"], 404);
    }
}
