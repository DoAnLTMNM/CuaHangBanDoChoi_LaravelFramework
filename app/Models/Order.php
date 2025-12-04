<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    use HasFactory;

    protected $fillable = [
        'user_id',
        'fullname',
        'phone',
        'email',
        'address',
        'total_price',
        'status',
        'payment_method',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper: chuyển status sang tiếng Việt
    public function getStatusTextAttribute()
    {
        $map = [
            'pending' => 'Chờ xác nhận',
            'processing' => 'Đang xử lý',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã hủy',
        ];

        return $map[$this->status] ?? $this->status;
    }

    public function getPaymentStatusTextAttribute()
    {
        // Nếu thanh toán COD và đang pending → đổi text
        if ($this->payment_method === 'cod' && $this->payment_status === 'pending') {
            return 'Chờ xác nhận';
        }

        $map = [
            'pending' => 'Chờ thanh toán',
            'unpaid' => 'Chưa thanh toán',
            'paid' => 'Đã thanh toán',
            'failed' => 'Thanh toán thất bại',
        ];

        return $map[$this->payment_status] ?? $this->payment_status;
    }
}
