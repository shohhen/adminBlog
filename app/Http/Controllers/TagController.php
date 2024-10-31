<?php

namespace App\Http\Controllers;

use Canvas\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class TagController extends Controller
{
    /**
     * Display a listing of the tags with pagination.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $limit = 10;

        $tags = Tag::query()
            ->select('id', 'name', 'created_at')
            ->latest()
            ->withCount('posts')
            ->paginate($limit);

        return response()->json([
            'data' => $tags->items(),
            'page' => $tags->currentPage(),
            'pages' => $tags->lastPage(),
            'total' => $tags->total(),
        ], 200);
    }

    /**
     * Display the specified tag by ID.
     *
     * @param  string  $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $tag = Tag::query()->findOrFail($id);

        return response()->json([
            'data' => $tag,
        ], 200);
    }

    /**
     * Display the posts associated with the specified tag, with pagination.
     *
     * @param  string  $id
     * @return JsonResponse
     */
    public function posts(string $id): JsonResponse
    {
        $limit = 10;

        $tag = Tag::query()->findOrFail($id);

        $posts = $tag->posts()
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