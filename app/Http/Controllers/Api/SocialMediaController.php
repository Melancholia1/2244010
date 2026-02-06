<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SocialMedia;
use Illuminate\Http\JsonResponse;

class SocialMediaController extends Controller
{
    /**
     * Get active social media links
     */
    public function index(): JsonResponse
    {
        $socialMedia = SocialMedia::where('is_active', true)
            ->orderBy('order_index', 'asc')
            ->get();

        $socialMediaData = $socialMedia->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'link_url' => $item->link,
                'icon' => $item->icon,
                'order_index' => $item->order_index,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $socialMediaData->values()->all()
        ]);
    }
}

