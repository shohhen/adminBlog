<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('viewer')->group(function () {
    // Post routes...
    Route::prefix('posts')->group(function () {
        Route::get('/', [PostController::class, "index"]);
        Route::get('{id}', [PostController::class, "show"]);
        Route::get('{id}/stats', [PostController::class, "stats"]);
    });

    // Tag routes...
    Route::prefix('tags')->group(function () {
        Route::get('/', [TagController::class, "index"]);
        Route::get('{id}', [TagController::class, "show"]);
        Route::get('{id}/posts', [TagController::class, "posts"]);
    });

    // Topic routes...
    Route::prefix('topics')->group(function () {
        Route::get('/', [TopicController::class, "index"]);
        Route::get('{id}', [TopicController::class, "show"]);
        Route::get('{id}/posts', [TopicController::class, "posts"]);
    });

    // User routes...
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, "index"]);
        Route::get('{id}', [UserController::class, "show"]);
        Route::get('{id}/posts', [UserController::class, "posts"]);
    });

    // Search routes...
    Route::prefix('search')->group(function () {
        Route::get('posts', [SearchController::class, "posts"]);
        Route::get('tags', [SearchController::class, "tags"]);
        Route::get('topics', [SearchController::class, "topics"]);
        Route::get('users', [SearchController::class, "users"]);
    });
});