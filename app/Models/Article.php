<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Article extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'title', 'slug', 'excerpt', 'table_of_contents', 'content',
        'status', 'published_at', 'featured_image',
        'meta_title', 'meta_description', 'meta_keywords',
        'category_blog_id',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    /**
     * Get the category that owns the article.
     */
    public function categoryBlog()
    {
        return $this->belongsTo(CategoryBlog::class, 'category_blog_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
