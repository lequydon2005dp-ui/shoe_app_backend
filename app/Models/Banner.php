<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $table = 'banner';

    protected $fillable = [
        'name',
        'image_url',
        'link'
    ];

    // Nếu bạn dùng cột `name` thay vì `title`
    public function getTitleAttribute()
    {
        return $this->name;
    }
}
