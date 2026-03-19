<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function list(Request $request)
    {
        $limit = ($request->limit) ?? 10;
        $categorys = Category::query()
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
        return response()->json($categorys);
    }
}
