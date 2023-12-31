<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\FollowsController;
use App\Http\Controllers\LikesController;
use App\Http\Controllers\SoundsController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\UsersController;
use App\Http\Resources\UsersResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return new UsersResource($request->user());
});

// Public routes

Route::post('/login', [AuthController::class, 'login']);
Route::post('/signup', [AuthController::class, 'signup']);

// Protected routes

Route::group(['middleware' => ['auth:sanctum']], function () {
    // Workaround as described here: https://stackoverflow.com/questions/65008650/how-to-use-put-method-in-laravel-api-with-file-upload
    Route::post('/sounds/{slug}', [SoundsController::class, 'update']);
    Route::post('/users/{slug}', [UsersController::class, 'update']);
    Route::get('/users/{slug}', [UsersController::class, 'show']);

    Route::apiResource('/sounds', SoundsController::class);
    Route::apiResource('/follows', FollowsController::class);
    Route::apiResource('/comments', CommentsController::class);
    Route::apiResource('/likes', LikesController::class);

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/upload', [UploadController::class, 'upload'])->name('upload.post');
});
