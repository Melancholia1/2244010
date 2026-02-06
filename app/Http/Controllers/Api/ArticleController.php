<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Get published articles
     */
    public function index(Request $request): JsonResponse
    {
        $query = Article::with('categoryBlog')
            ->where('status', 'published')
            ->where(function($q) {
                // Show articles that are published:
                // 1. published_at is null (draft yang langsung di-publish) - tampilkan
                // 2. published_at is in the past or today - tampilkan
                // 3. published_at is in the future but within 1 year - tampilkan untuk scheduled posts
                $q->whereNull('published_at')
                  ->orWhere('published_at', '<=', now())
                  ->orWhere('published_at', '<=', now()->addYear());
            })
            ->orderByRaw('COALESCE(published_at, created_at) DESC');

        // Filter by category if provided
        if ($request->has('category')) {
            $categorySlug = $request->category;
            $category = \App\Models\CategoryBlog::where('is_active', true)
                ->whereRaw('LOWER(REPLACE(name, " ", "-")) = ?', [strtolower($categorySlug)])
                ->first();
            
            if ($category) {
                $query->where('category_blog_id', $category->id);
            }
        }

        // Featured articles - show articles with featured_image if available, otherwise show latest
        if ($request->has('featured') && $request->featured == 'true') {
            // Prioritize articles with featured_image, but also include others if needed
            $query->orderByRaw('CASE WHEN featured_image IS NOT NULL THEN 0 ELSE 1 END')
                  ->limit(5);
        }

        // Pagination
        $perPage = $request->get('per_page', 10);
        $articles = $query->paginate($perPage);

        // Transform articles to include category data
        $articlesData = $articles->getCollection()->map(function ($article) {
            // Handle featured_image path
            $featuredImage = null;
            if ($article->featured_image) {
                // If already a full URL, use it as is
                if (filter_var($article->featured_image, FILTER_VALIDATE_URL)) {
                    $featuredImage = $article->featured_image;
                } else {
                    // Otherwise, prepend storage path
                    $featuredImage = asset('storage/' . $article->featured_image);
                }
            }

            return [
                'id' => $article->id,
                'title' => $article->title,
                'slug' => $article->slug,
                'excerpt' => $article->excerpt,
                'table_of_contents' => $article->table_of_contents,
                'content' => $article->content,
                'status' => $article->status,
                'published_at' => $article->published_at?->toISOString(),
                'featured_image' => $featuredImage,
                'meta_title' => $article->meta_title,
                'meta_description' => $article->meta_description,
                'meta_keywords' => $article->meta_keywords,
                'category_blog' => $article->categoryBlog ? [
                    'id' => $article->categoryBlog->id,
                    'name' => $article->categoryBlog->name,
                    'slug' => \Str::slug($article->categoryBlog->name),
                ] : null,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $articlesData->values()->all(),
            'pagination' => [
                'current_page' => $articles->currentPage(),
                'last_page' => $articles->lastPage(),
                'per_page' => $articles->perPage(),
                'total' => $articles->total(),
            ]
        ]);
    }

    /**
     * Get single article by slug
     */
    public function show(string $slug): JsonResponse
    {
        $article = Article::with('categoryBlog')
            ->where('slug', $slug)
            ->where('status', 'published')
            ->where(function($q) {
                $q->whereNull('published_at')
                  ->orWhere('published_at', '<=', now())
                  ->orWhere('published_at', '<=', now()->addYear());
            })
            ->first();

        if (!$article) {
            return response()->json([
                'success' => false,
                'message' => 'Article not found'
            ], 404);
        }

        // Handle featured_image path
        $featuredImage = null;
        if ($article->featured_image) {
            // If already a full URL, use it as is
            if (filter_var($article->featured_image, FILTER_VALIDATE_URL)) {
                $featuredImage = $article->featured_image;
            } else {
                // Otherwise, prepend storage path
                $featuredImage = asset('storage/' . $article->featured_image);
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $article->id,
                'title' => $article->title,
                'slug' => $article->slug,
                'excerpt' => $article->excerpt,
                'table_of_contents' => $article->table_of_contents,
                'content' => $article->content,
                'status' => $article->status,
                'published_at' => $article->published_at?->toISOString(),
                'featured_image' => $featuredImage,
                'meta_title' => $article->meta_title,
                'meta_description' => $article->meta_description,
                'meta_keywords' => $article->meta_keywords,
                'category_blog' => $article->categoryBlog ? [
                    'id' => $article->categoryBlog->id,
                    'name' => $article->categoryBlog->name,
                    'slug' => \Str::slug($article->categoryBlog->name),
                ] : null,
            ]
        ]);
    }

    /**
     * Get recent articles
     */
    public function recent(Request $request): JsonResponse
    {
        $limit = $request->get('limit', 5);
        
        $articles = Article::with('categoryBlog')
            ->where('status', 'published')
            ->where(function($q) {
                $q->whereNull('published_at')
                  ->orWhere('published_at', '<=', now())
                  ->orWhere('published_at', '<=', now()->addYear());
            })
            ->orderByRaw('COALESCE(published_at, created_at) DESC')
            ->limit($limit)
            ->get();

        $articlesData = $articles->map(function ($article) {
            // Handle featured_image path
            $featuredImage = null;
            if ($article->featured_image) {
                // If already a full URL, use it as is
                if (filter_var($article->featured_image, FILTER_VALIDATE_URL)) {
                    $featuredImage = $article->featured_image;
                } else {
                    // Otherwise, prepend storage path
                    $featuredImage = asset('storage/' . $article->featured_image);
                }
            }

            return [
                'id' => $article->id,
                'title' => $article->title,
                'slug' => $article->slug,
                'excerpt' => $article->excerpt,
                'table_of_contents' => $article->table_of_contents,
                'content' => $article->content,
                'status' => $article->status,
                'published_at' => $article->published_at?->toISOString(),
                'featured_image' => $featuredImage,
                'meta_title' => $article->meta_title,
                'meta_description' => $article->meta_description,
                'meta_keywords' => $article->meta_keywords,
                'category_blog' => $article->categoryBlog ? [
                    'id' => $article->categoryBlog->id,
                    'name' => $article->categoryBlog->name,
                    'slug' => \Str::slug($article->categoryBlog->name),
                ] : null,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $articlesData->values()->all()
        ]);
    }
}

