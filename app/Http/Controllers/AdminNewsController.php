<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Models\News;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminNewsController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $search = $request->get('search');
        if (!is_null($search)) {
            $news = News::search($search, ['id', 'title', 'updated_at', 'status']);
        } else {
            $news = [];
        }
        return response()->json($news);
    }

    public function create(StorePostRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $newPost = new News();
        $newPost->title = $validated['title'];
        $newPost->body = $validated['body'];
        $newPost->updated_at = new \DateTime();
        if ($newPost->save()) {
            return response()->json(['status' => 'Created'], 201);
        }

        return response()->json(['status' => 'Error'], 400);
    }

    /**
     * Display the specified resource.
     *
     */
    public function show($id): JsonResponse
    {
        $post = News::select(['id', 'title', 'body', 'updated_at', 'status'])
            ->where('id', '=', $id)->first();
        if (is_null($post)) {
            return response()->json('Post not found', 404);
        }
        return response()->json($post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|string'
        ]);
        $post = News::find($id);
        if (is_null($post)) {
            return response()->json('Post not found', 404);
        }

        $post->status = $validated['status'];
        $post->save();
        if ($post->save()) {
            return response()->json(['status' => 'Updated'], 204);
        }
        return response()->json(['status' => 'Error'], 400);
    }
}
