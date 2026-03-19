<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Orderdetail extends Model
{
    protected $table = 'orderdetail';
    
    protected $fillable = ['order_id', 'product_id', 'quantity', 'price'];
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
