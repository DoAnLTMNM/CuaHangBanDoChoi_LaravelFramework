<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProductItem;
use App\Models\ProductFeature;

class Product extends Model
{
    use HasFactory;
    // Các cột có thể gán hàng loạt
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'category_id',
        'brand',
        'is_active',
        'image', // nhớ có trường này để lưu ảnh
    ];

    // Quan hệ: 1 sản phẩm thuộc 1 danh mục
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Quan hệ: 1 sản phẩm có nhiều item (biến thể)
    public function items()
    {
        return $this->hasMany(ProductItem::class);
    }

    // Quan hệ: 1 sản phẩm có nhiều tính năng
    public function features()
    {
        return $this->hasMany(ProductFeature::class);
    }

    // Giảm giá
    public function discount()
    {
        return $this->hasOne(Discount::class);
    }
}
