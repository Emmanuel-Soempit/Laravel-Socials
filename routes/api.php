<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


//Unprotected routes
Route::post('/register-user', [AuthController::class,'register']);
Route::post('/login', [AuthController::class,'login']);


//protected routes
Route::group(['middleware'=> ['auth:sanctum']], function () {
    Route::get('/logout', [AuthController::class,'logout']);

    Route::post('/create-post', [PostController::class,'store']);
    Route::get('/get-post/{id}', [PostController::class,'show']);
    Route::put('/update-post/{id}', [PostController::class,'update']);
    Route::delete('/delete-post/{id}', [PostController::class,'destroy']);
    
    Route::post('/create-comment', [CommentController::class,'store']);
    Route::get('/get-comment/{id}', [CommentController::class,'show']);
    Route::put('/update-comment/{id}', [CommentController::class,'update']);
    Route::delete('/delete-comment/{id}', [CommentController::class,'destroy']);
});

