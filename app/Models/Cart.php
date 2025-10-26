<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
    ];

    // Mỗi cart liên kết với một sản phẩm
    public function product() {
        return $this->belongsTo(Product::class);
    }

    // Mỗi cart liên kết với một user (nếu web có đăng nhập)
    public function user() {
        return $this->belongsTo(User::class);
    }
}
