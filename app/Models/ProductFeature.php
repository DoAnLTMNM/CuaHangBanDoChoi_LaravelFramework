<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductFeature extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'feature', 'image_url'];

    // Mỗi feature thuộc về một sản phẩm
    public function product() {
        return $this->belongsTo(Product::class);
    }
}
