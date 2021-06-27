<?php


namespace App\Http\Controllers;


use App\Models\News;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserNewsController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $search = $request->get('search');
        if (!is_null($search)) {
            $news = News::search($search, ['id', 'title', 'updated_at']);
        } else {
            $news = [];
        }
        return response()->json($news);
    }

    public function show($id): JsonResponse
    {
        $post = News::select(['id', 'title', 'body', 'updated_at'])
            ->where('id', '=', $id)->first();
        if (is_null($post)) {
            return response()->json('Post not found', 404);
        }
        return response()->json($post);
    }
}
