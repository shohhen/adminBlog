<?php

namespace App\Http\Controllers;

use Canvas\Models\Post;
use Canvas\Models\Tag;
use Canvas\Models\Topic;
use Canvas\Services\StatsAggregator;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $limit = 10;

        $posts = Post::query()
            ->select('id', 'title', 'summary', 'featured_image', 'published_at', 'created_at', 'updated_at')
            ->whereNotNull('published_at')
            ->latest()
            ->paginate($limit);

        return response()->json([
            'data' => $posts->items(),
            'page' => $posts->currentPage(),
            'pages' => $posts->lastPage(),
            'total' => $posts->total(),
        ], 200);
    }

    /**
     * Display the specified published post with tags and topics.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $post = Post::query()
            ->whereNotNull('published_at')
            ->with('tags:name,slug', 'topic:name,slug')
            ->findOrFail($id);

        return response()->json([
            'post' => $post,
            'tags' => Tag::query()->get(['name', 'slug']),
            'topics' => Topic::query()->get(['name', 'slug']),
        ], 200);
    }

    /**
     * Display stats for the specified published post.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function stats(string $id): JsonResponse
    {
        $post = Post::query()
            ->whereNotNull('published_at')
            ->findOrFail($id);

        $stats = new StatsAggregator();
        $results = $stats->getStatsForPost($post);

        return response()->json($results, 200);
    }
}
