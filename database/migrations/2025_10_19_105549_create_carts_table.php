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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            // Liên kết đến bảng 'user' và tự động xóa khi user bị xóa
            $table->foreignId('user_id')->constrained('user')->onDelete('cascade');
            // Liên kết đến bảng 'products' và tự động xóa khi sản phẩm bị xóa
            $table->foreignId('product_id')->constrained('product')->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->timestamps();

            // Đảm bảo mỗi user chỉ có 1 dòng cho mỗi sản phẩm
            $table->unique(['user_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
