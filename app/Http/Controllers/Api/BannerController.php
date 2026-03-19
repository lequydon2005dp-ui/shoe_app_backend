<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BannerController extends Controller
{
    public function list(Request $request)
    {
        $limit = $request->query('limit', 10);

        $banners = Banner::select('id', 'name as title', 'image_url', 'link')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

        $banners->transform(function ($banner) {
            if ($banner->image_url && !str_starts_with($banner->image_url, 'http')) {
                $banner->image_url = asset('storage/' . $banner->image_url);
            }
            return $banner;
        });

        return response()->json($banners);
    }
}
