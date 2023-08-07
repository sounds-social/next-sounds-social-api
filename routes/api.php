<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FollowsController;
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
    Route::apiResource('/sounds', SoundsController::class);
    Route::apiResource('/follows', FollowsController::class);

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/upload', [UploadController::class, 'upload'])->name('upload.post');
    Route::get('/users/{slug}', [UsersController::class, 'show']);
});
