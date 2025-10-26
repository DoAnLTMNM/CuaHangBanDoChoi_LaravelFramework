<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProductItem;
use App\Models\ProductFeature;

class Product extends Model
{
    use HasFactory;
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    // Trong App\Models\Product.php
    public function items()
    {
        return $this->hasMany(ProductItem::class);
    }

    public function features()
    {
        return $this->hasMany(ProductFeature::class);
    }
}
