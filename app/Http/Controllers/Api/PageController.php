<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Get pages by section (for footer, navbar, etc)
     */
    public function index(Request $request): JsonResponse
    {
        $query = Page::where('is_published', true)
            ->where(function($q) {
                $q->whereNull('published_at')
                  ->orWhere('published_at', '<=', now());
            });

        // Filter by section if provided
        if ($request->has('section')) {
            $query->where('section', $request->section);
        } else {
            // If no section specified, only get pages that have section (for footer/navbar)
            $query->whereNotNull('section');
        }

        // Order by sort_order, then by title
        $pages = $query->orderBy('sort_order', 'asc')
            ->orderBy('title', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $pages
        ]);
    }

    /**
     * Get page by slug
     */
    public function show(string $slug): JsonResponse
    {
        $page = Page::where('slug', $slug)
            ->where('is_published', true)
            ->where(function($query) {
                $query->whereNull('published_at')
                      ->orWhere('published_at', '<=', now());
            })
            ->first();

        if (!$page) {
            return response()->json([
                'success' => false,
                'message' => 'Page not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $page
        ]);
    }
}








