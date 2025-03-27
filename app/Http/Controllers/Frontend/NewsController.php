<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\NewsResource;
use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /**
     * Lấy danh sách tin tức.
     */
    public function index()
    {
        $news = News::with(['category', 'author'])->get();
        return response()->json([
            'success' => true,
            'message' => 'News list retrieved successfully',
            'data' => NewsResource::collection($news),
        ]);
    }

    /**
     * Thêm tin tức mới.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
            'author_id' => 'required|exists:users,id',
            'news_category_id' => 'required|exists:news_categories,news_category_id',
            'published_date' => 'nullable|date',
            'status' => 'required|string|in:draft,published',
        ]);

        $validated['published_date'] = $validated['published_date'] ?? now();

        $news = News::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'News created successfully',
            'data' => new NewsResource($news),
        ], 201);
    }

    /**
     * Hiển thị chi tiết tin tức.
     */
    public function show(News $news)
    {
        $news->load(['category', 'author']);
        return response()->json([
            'success' => true,
            'message' => 'News retrieved successfully',
            'data' => new NewsResource($news),
        ]);
    }

    /**
     * Cập nhật tin tức.
     */
    public function update(Request $request, News $news)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string',
            'content' => 'sometimes|string',
            'news_category_id' => 'sometimes|exists:news_categories,news_category_id',
            'status' => 'sometimes|string|in:draft,published',
        ]);

        $news->update($validated);
        return response()->json([
            'success' => true,
            'message' => 'News updated successfully',
            'data' => new NewsResource($news),
        ]);
    }

    /**
     * Xóa tin tức.
     */
    public function destroy(News $news)
    {
        $news->delete();

        return response()->json([
            'success' => true,
            'message' => 'News deleted successfully',
            'data' => null,
        ]);
    }
}
