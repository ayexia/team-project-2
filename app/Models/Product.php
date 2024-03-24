<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Product extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'description',
        'price',
        'quantity',
        'image_url',
        'colour',
        'brand',
        'sizes',
        'category_id',
        'available',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews()
    {
    return $this->hasMany(Review::class);
    }

    
    public function getAvailableSizes()
    {
        return json_decode($this->sizes);
    }

}
