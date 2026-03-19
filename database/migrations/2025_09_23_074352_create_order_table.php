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
        Schema::create('order', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('id');
            $table->foreignId('user_id')      // Tự động tạo cột unsignedBigInteger
                ->constrained('users') // Liên kết với cột 'id' trên bảng 'users'
                ->onDelete('cascade'); // Nếu user bị xóa, các đơn hàng của họ cũng sẽ bị xóa
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('address');
            $table->string('payment_method');
            $table->decimal('shipping_cost', 10, 2);
            $table->decimal('total_amount', 10, 2); // Tổng tiền cuối cùng (đã bao gồm ship)
            $table->string('status')->default('pending'); // pending, processing, completed, cancelled
            $table->text('cancel_reason')->nullable()->after('status');
            $table->timestamp('cancelled_at')->nullable()->after('cancel_reason');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order');
    }
};
