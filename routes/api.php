<?php

use Illuminate\Support\Facades\Route;
use Canvas\Models\Post;
use Canvas\Models\Tag;
use Canvas\Models\Topic;
use Canvas\Models\User;
use Canvas\Events\PostViewed;

// Retrieve all published posts
Route::get('/posts', function () {
    return Post::published()->get();
});

// Retrieve all draft posts
Route::get('/posts/drafts', function () {
    return Post::draft()->get();
});

// Retrieve a single post by slug and record a view
Route::get('/posts/{slug}', function ($slug) {
    $post = Post::with('user', 'tags', 'topic')->firstWhere('slug', $slug);
    if ($post) {
        event(new PostViewed($post)); // Record a view
    }
    return $post ?: response()->json(['error' => 'Post not found'], 404);
});

// Retrieve a tag by slug with its associated posts
Route::get('/tags/{slug}', function ($slug) {
    $tag = Tag::with('posts')->firstWhere('slug', $slug);
    return $tag ?: response()->json(['error' => 'Tag not found'], 404);
});

// Retrieve a topic by slug with its associated posts
Route::get('/topics/{slug}', function ($slug) {
    $topic = Topic::with('posts')->firstWhere('slug', $slug);
    return $topic ?: response()->json(['error' => 'Topic not found'], 404);
});

// Retrieve a user by ID, username, or email, and include their published posts with topics
Route::get('/users/{identifier}', function ($identifier) {
    $user = User::where('id', $identifier)
                ->orWhere('username', $identifier)
                ->orWhere('email', $identifier)
                ->first();

    if ($user) {
        return [
            'user' => $user,
            'posts' => $user->posts()->published()->with('topic')->get()
        ];
    }

    return response()->json(['error' => 'User not found'], 404);
});
