<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Favorite;

class Product extends Model
{
    protected $table = 'product';
    

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
}
