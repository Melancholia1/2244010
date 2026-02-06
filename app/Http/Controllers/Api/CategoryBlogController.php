<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CategoryBlog;
use Illuminate\Http\JsonResponse;

class CategoryBlogController extends Controller
{
    /**
     * Get active categories
     */
    public function index(): JsonResponse
    {
        $categories = CategoryBlog::where('is_active', true)
            ->orderBy('name', 'asc')
            ->get();

        // Get article count for each category (using same query logic as ArticleController)
        $categoriesWithCount = $categories->map(function ($category) {
            $count = \App\Models\Article::where('status', 'published')
                ->where('category_blog_id', $category->id)
                ->where(function($q) {
                    $q->whereNull('published_at')
                      ->orWhere('published_at', '<=', now())
                      ->orWhere('published_at', '<=', now()->addYear());
                })
                ->count();

            return [
                'id' => $category->id,
                'name' => $category->name,
                'description' => $category->description,
                'slug' => \Str::slug($category->name),
                'article_count' => $count,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $categoriesWithCount
        ]);
    }
}

