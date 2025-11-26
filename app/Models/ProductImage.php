<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    // Cho phép gán hàng loạt
    protected $fillable = [
        'product_id',
        'image',
    ];

    // Quan hệ ngược về Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
}
