<?php

namespace App\Http\Controllers;

use Canvas\Models\Topic;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class TopicController extends Controller
{
    /**
     * Display a listing of topics with pagination.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $limit = 10;

        $topics = Topic::query()
            ->select('id', 'name', 'created_at')
            ->latest()
            ->withCount('posts')
            ->paginate($limit);

        return response()->json([
            'data' => $topics->items(),
            'page' => $topics->currentPage(),
            'pages' => $topics->lastPage(),
            'total' => $topics->total(),
        ], 200);
    }

    /**
     * Display the specified topic by ID.
     *
     * @param  string  $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $topic = Topic::query()->findOrFail($id);

        return response()->json([
            'data' => $topic,
        ], 200);
    }

    /**
     * Display posts associated with the specified topic, with pagination.
     *
     * @param  string  $id
     * @return JsonResponse
     */
    public function posts(string $id): JsonResponse
    {
        $limit = 10; // Set a fixed pagination limit

        $topic = Topic::query()->findOrFail($id);

        $posts = $topic->posts()
            ->withCount('views')
            ->paginate($limit);

        return response()->json([
            'data' => $posts->items(),
            'page' => $posts->currentPage(),
            'pages' => $posts->lastPage(),
            'total' => $posts->total(),
        ], 200);
    }
}