<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SettingSeo;
use Illuminate\Http\JsonResponse;

class SettingSeoController extends Controller
{
    /**
     * Get SEO settings
     */
    public function index(): JsonResponse
    {
        $seoSettings = SettingSeo::first();

        return response()->json([
            'success' => true,
            'data' => $seoSettings ?? [
                'meta_title' => null,
                'meta_description' => null,
                'meta_keywords' => null,
                'robots' => null,
            ]
        ]);
    }
}









