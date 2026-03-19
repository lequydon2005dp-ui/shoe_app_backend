<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $table = 'order';

    use HasFactory;

    protected $fillable = [
        'user_id',
        'address',
        'phone',
        'email',
        'payment_method',
        'shipping_cost',
        'total_amount',
        'status',
        'cancel_reason', 
        'cancelled_at'
    ];
    protected $casts = [
        'total_amount' => 'float',  // <-- DÒNG QUAN TRỌNG
        'shipping_cost' => 'float', // Ép kiểu cả phí ship
    ];

    public function cancel($reason)
    {
        $this->status = 'cancelled';
        $this->cancel_reason = $reason;
        $this->cancelled_at = now();
        $this->save();
    }
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function orderdetails()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }
}
