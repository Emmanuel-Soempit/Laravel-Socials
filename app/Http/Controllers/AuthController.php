<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    //
    

    public function login(AuthRequest $request)
    {
        $request->validated();
        $credentials = $request->only("email", "password");

        //Authenticate user
        if (auth()->attempt($credentials)) {

            $user = auth()->user();
            $token = $user->createToken('laravelSocialsToken', ['*'], now()->addDay())->plainTextToken;
            
            $userWithPosts = User::with('posts')->findOrFail($user->id);
            
            $response = [
                'message' => 'Authentication successful',
                'user' => $userWithPosts,
                'token' => $token
            ];

            return response($response);
        }

        $response = [
            'message'=>'Error Logging in',
            'Error'=> 'Authentication failed, invalid credentials'
        ];
        return response($response, 401);

    }

    public function register(RegisterRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['password'] = bcrypt($validatedData['password']);

        $user = User::create($validatedData);
        $response = [
            "message" => "User registered succesfully",
            "data" => $user
        ];

        return response($response, 201);

    }

    public function logout(Request $request)
    { 
        auth()->user()->tokens()->delete();    

        $response = [
            "message"=> "Log out successful",
        ];

        return response($response, 201);
    }

    public function delete(Request $request)
    {

    }

}
