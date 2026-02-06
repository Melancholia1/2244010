<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\CategoryBlogController;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\SettingSeoController;
use App\Http\Controllers\Api\SocialMediaController;
use App\Http\Controllers\Api\CommentController;

// Frontend Routes
Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/category', function () {
    return view('category');
})->name('category');

Route::get('/blog/{slug}', function ($slug) {
    return view('blog-details');
})->name('blog.show');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::post('/contact', function () {
    // Handle contact form submission
    return back()->with('success', 'Message sent successfully!');
})->name('contact.submit');

Route::get('/author/{id}', function ($id) {
    return view('author-profile');
})->name('author.profile');

Route::get('/search', function () {
    return view('search-results');
})->name('search');

Route::get('/page/{slug}', function ($slug) {
    return view('page');
})->name('page.show');

// API Routes for Frontend
Route::prefix('api')->group(function () {
    // Banners
    Route::get('/banners', [BannerController::class, 'index']);
    
    // Articles
    Route::get('/articles', [ArticleController::class, 'index']);
    Route::get('/articles/recent', [ArticleController::class, 'recent']);
    Route::get('/articles/{slug}', [ArticleController::class, 'show']);
    Route::get('/articles/{slug}/comments', [CommentController::class, 'index']);
    Route::post('/articles/{slug}/comments', [CommentController::class, 'store']);
    
    // Category Blogs
    Route::get('/categories', [CategoryBlogController::class, 'index']);
    
    // Pages
    Route::get('/pages', [PageController::class, 'index']);
    Route::get('/pages/{slug}', [PageController::class, 'show']);
    
    // SEO Settings
    Route::get('/seo-settings', [SettingSeoController::class, 'index']);
    
    // Social Media
    Route::get('/social-media', [SocialMediaController::class, 'index']);
});
