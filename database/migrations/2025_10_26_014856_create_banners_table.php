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
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('image_url'); // đường dẫn ảnh banner
            $table->enum('target_type', ['category', 'product', 'custom'])->default('custom'); // loại banner
            $table->unsignedBigInteger('target_id')->nullable(); // id danh mục hoặc sản phẩm
            $table->string('link_url')->nullable(); // link thủ công (nếu cần)
            $table->boolean('is_active')->default(true); // banner đang hoạt động không
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
