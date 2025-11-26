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
                Schema::dropIfExists('product_items');
        Schema::dropIfExists('product_features');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Nếu muốn rollback, bạn có thể tạo lại bảng với cấu trúc cũ
        Schema::create('product_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->string('item_name');
            $table->integer('quantity')->default(0);
            $table->string('image_url')->nullable();
            $table->timestamps();
        });

        Schema::create('product_features', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->string('feature');
            $table->string('image_url')->nullable();
            $table->timestamps();
        });
    }
};
