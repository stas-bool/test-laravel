<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Models\News;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $search = $request->get('search');
        if (!is_null($search)) {
            $user = Auth::user();
            $select = ['id', 'title', 'updated_at'];
            if (!is_null($user) && $user->hasRole('admin')) {
                $select[] = 'status';
            }
            $news = News::search($search, $select);
        } else {
            $news = [];
        }
        return response()->json($news);
    }

    public function store(StorePostRequest $request): JsonResponse
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

    public function show(News $news): JsonResponse
    {
        $user = Auth::user();
        if (!is_null($user) && $user->hasRole('user')) {
            $news->setHidden(['status']);
        }
        return response()->json($news);
    }

    public function update(Request $request, News $news): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|string'
        ]);

        $news->status = $validated['status'];
        $news->save();
        if ($news->save()) {
            return response()->json(['status' => 'Updated'], 204);
        }
        return response()->json(['status' => 'Error'], 400);
    }
}
