<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    /**
     * Get active banners for hero slider
     */
    public function index(Request $request): JsonResponse
    {
        $position = $request->query('position', 'hero'); // default 'hero'
        $ignoreDates = $request->query('ignore_dates', false); // for development/testing
        $now = now();
        
        // Start with active banners
        $query = Banner::where('is_active', true);
        
        // Apply date range filter only if not ignoring dates
        if (!$ignoreDates) {
            // Compare datetime properly - start_date should be <= now, end_date should be >= now
            // Use Carbon instance directly for proper datetime comparison
            $query->where(function ($query) use ($now) {
                $query->whereNull('start_date')
                    ->orWhere('start_date', '<=', $now);
            })
            ->where(function ($query) use ($now) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', $now);
            });
        }
        
        // Filter by position (case-insensitive)
        if ($position) {
            $query->whereRaw('LOWER(position) = ?', [strtolower($position)]);
        }
        
        // Get all matching banners for debugging
        $allBanners = Banner::whereRaw('LOWER(position) = ?', [strtolower($position)])->get();
        
        $banners = $query->orderBy('order_index', 'asc')->get();
        
        // Log for debugging (remove in production if needed)
        \Log::info("Banner API Request", [
            'position' => $position,
            'now' => $now->toDateTimeString(),
            'timezone' => $now->timezone->getName(),
            'all_banners_count' => $allBanners->count(),
            'all_banners' => $allBanners->map(function($b) {
                return [
                    'id' => $b->id,
                    'title' => $b->title,
                    'position' => $b->position,
                    'is_active' => $b->is_active,
                    'start_date' => $b->start_date?->toDateTimeString(),
                    'end_date' => $b->end_date?->toDateTimeString(),
                ];
            })->toArray(),
            'filtered_count' => $banners->count(),
            'banner_ids' => $banners->pluck('id')->toArray()
        ]);

        // Transform banners to include proper image URLs
        $bannersData = $banners->map(function ($banner) {
            // Handle image_url path
            $imageUrl = null;
            if ($banner->image_url) {
                // If already a full URL, use it as is
                if (filter_var($banner->image_url, FILTER_VALIDATE_URL)) {
                    $imageUrl = $banner->image_url;
                } else {
                    // Otherwise, prepend storage path
                    $imageUrl = asset('storage/' . $banner->image_url);
                }
            }

            return [
                'id' => $banner->id,
                'title' => $banner->title,
                'subtitle' => $banner->subtitle,
                'image_url' => $imageUrl,
                'link_url' => $banner->link_url,
                'position' => $banner->position,
                'order_index' => $banner->order_index,
                'is_active' => $banner->is_active,
                'start_date' => $banner->start_date?->toISOString(),
                'end_date' => $banner->end_date?->toISOString(),
            ];
        });

        // Add debug info in development
        $debugInfo = [];
        if (config('app.debug')) {
            $debugInfo = [
                'debug' => [
                    'position_requested' => $position,
                    'now' => $now->toDateTimeString(),
                    'timezone' => $now->timezone->getName(),
                    'all_banners_count' => $allBanners->count(),
                    'filtered_count' => $banners->count(),
                    'ignore_dates' => $ignoreDates,
                ]
            ];
        }
        
        return response()->json(array_merge([
            'success' => true,
            'data' => $bannersData->values()->all()
        ], $debugInfo));
    }
}


