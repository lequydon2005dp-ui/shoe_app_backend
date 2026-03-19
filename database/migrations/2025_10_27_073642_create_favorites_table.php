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
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('user')
                ->onDelete('cascade')
                ->comment('ID người dùng yêu thích sản phẩm');

            $table->foreignId('product_id')
                ->constrained('product')
                ->onDelete('cascade')
                ->comment('ID sản phẩm được yêu thích');

            $table->timestamps();

            // Ngăn trùng lặp: 1 user chỉ yêu thích 1 sản phẩm 1 lần
            $table->unique(['user_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};
