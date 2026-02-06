<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Article;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'article_id',
        'name',
        'email',
        'website',
        'comment',
        'is_approved',
        'ip_address',
        'user_agent',
    ];

    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
