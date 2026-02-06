<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryBlog extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryBlogFactory> */
    use HasFactory;

    protected $table = 'category_blog';

    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the articles for the category.
     */
    public function articles()
    {
        return $this->hasMany(Article::class, 'category_blog_id');
    }
}




