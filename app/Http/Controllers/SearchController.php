<?php

namespace App\Http\Controllers;

use Canvas\Models\Post;
use Canvas\Models\Tag;
use Canvas\Models\Topic;
use Canvas\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class SearchController extends Controller
{
    /**
     * Display a listing of posts.
     *
     * @return JsonResponse
     */
    public function posts(): JsonResponse
    {
        $posts = Post::query()
            ->select('id', 'title')
            ->latest()
            ->get()
            ->map(function ($post) {
                return [
                    'id' => $post->id,
                    'name' => $post->title,
                    'type' => 'Post',
                    'route' => 'edit-post'
                ];
            });

        return response()->json(['data' => $posts], 200);
    }

    /**
     * Display a listing of tags.
     *
     * @return JsonResponse
     */
    public function tags(): JsonResponse
    {
        $tags = Tag::query()
            ->select('id', 'name')
            ->latest()
            ->get()
            ->map(function ($tag) {
                return [
                    'id' => $tag->id,
                    'name' => $tag->name,
                    'type' => 'Tag',
                    'route' => 'edit-tag'
                ];
            });

        return response()->json(['data' => $tags], 200);
    }

    /**
     * Display a listing of topics.
     *
     * @return JsonResponse
     */
    public function topics(): JsonResponse
    {
        $topics = Topic::query()
            ->select('id', 'name')
            ->latest()
            ->get()
            ->map(function ($topic) {
                return [
                    'id' => $topic->id,
                    'name' => $topic->name,
                    'type' => 'Topic',
                    'route' => 'edit-topic'
                ];
            });

        return response()->json(['data' => $topics], 200);
    }

    /**
     * Display a listing of users.
     *
     * @return JsonResponse
     */
    public function users(): JsonResponse
    {
        $users = User::query()
            ->select('id', 'name', 'email')
            ->latest()
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'type' => 'User',
                    'route' => 'edit-user'
                ];
            });

        return response()->json(['data' => $users], 200);
    }
}
