<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->decimal('discount_percent', 5, 2)->nullable()->comment('Phần trăm giảm giá');
            $table->decimal('discount_amount', 10, 2)->nullable()->comment('Giảm giá theo số tiền');
            $table->dateTime('start_date')->nullable()->comment('Ngày bắt đầu giảm');
            $table->dateTime('end_date')->nullable()->comment('Ngày kết thúc giảm');
            $table->boolean('is_active')->default(true)->comment('Trạng thái giảm giá');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
