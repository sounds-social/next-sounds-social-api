<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SoundsController;
use App\Http\Controllers\TasksController;
use App\Http\Controllers\UploadController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public routes

Route::post('/login', [AuthController::class, 'login']);
Route::post('/signup', [AuthController::class, 'signup']);
Route::apiResource('/sounds', SoundsController::class);

// Protected routes

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Route::resource('/tasks', TasksController::class)
    Route::post('/upload', [UploadController::class, 'upload'])->name('upload.post');
});
