<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;

    protected $table = 'categories';
    protected $fillable = [
            'kodekategori',
            'namakategori',
            'status',
        ];

public function setKategoribarangAttribute($value): void
{
    $this->attributes['kodekategori'] = strtoupper(trim($value));
}

//
public function product()
    {
        return $this->hasMany(Produk::class, 'category_id');
    }
}
