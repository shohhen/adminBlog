<?php

namespace App\Http\Controllers;

use Canvas\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class UserController extends Controller
{
    /**
     * Display a listing of users with pagination.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $limit = 10;

        $users = User::query()
            ->select('id', 'name', 'email', 'avatar', 'role')
            ->latest()
            ->withCount('posts')
            ->paginate($limit);

        return response()->json([
            'data' => $users->items(),
            'page' => $users->currentPage(),
            'pages' => $users->lastPage(),
            'total' => $users->total(),
        ], 200);
    }

    /**
     * Display the specified user by ID.
     *
     * @param  string  $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $user = User::query()->withCount('posts')->find($id);

        return $user
            ? response()->json(['data' => $user], 200)
            : response()->json(['data' => null], 404);
    }

    /**
     * Display posts associated with the specified user, with pagination.
     *
     * @param  string  $id
     * @return JsonResponse
     */
    public function posts(string $id): JsonResponse
    {
        $limit = 10;

        $user = User::query()->find($id);

        if ($user) {
            $posts = $user->posts()
                ->withCount('views')
                ->paginate($limit);

            return response()->json([
                'data' => $posts->items(),
                'page' => $posts->currentPage(),
                'pages' => $posts->lastPage(),
                'total' => $posts->total(),
            ], 200);
        }

        return response()->json(['data' => null], 404);
    }
}
