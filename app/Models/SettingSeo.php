<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingSeo extends Model
{
    /** @use HasFactory<\Database\Factories\SettingSeoFactory> */
    use HasFactory;

    protected $table = 'setting_seo';

    protected $fillable = [
        'meta_title',
        'meta_description',
        'meta_keywords',
        'robots',
    ];
}




