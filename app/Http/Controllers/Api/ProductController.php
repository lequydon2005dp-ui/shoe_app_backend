<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function list(Request $request)
    {
        $limit = ($request->limit) ?? 10;
        $products = Product::query()
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
        return response()->json($products);
    }
    public function details($id)
    {
        // Sử dụng `with('category')` để tải thông tin danh mục cùng lúc (Eager Loading),
        // giúp tối ưu hiệu suất, tránh N+1 query problem.
        // Sử dụng `find()` thay vì `findOrFail()` để có thể trả về lỗi JSON tùy chỉnh.
        $product = Product::with('category')->find($id);

        // Nếu không tìm thấy sản phẩm, trả về lỗi 404 với thông báo rõ ràng.
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy sản phẩm với ID được cung cấp.'
            ], 404);
        }

        // Lấy các sản phẩm liên quan (sản phẩm cùng danh mục, trừ sản phẩm hiện tại).
        // Chỉ thực hiện truy vấn này nếu sản phẩm có danh mục.
        $relatedProducts = [];
        if ($product->category_id) {
            $relatedProducts = Product::where('category_id', $product->category_id)
                ->where('id', '!=', $id) // Loại trừ chính sản phẩm đang xem
                ->orderBy('created_at', 'desc') // Sắp xếp theo sản phẩm mới nhất
                ->limit(6) // Giới hạn số lượng sản phẩm liên quan
                ->get();
        }

        // Thêm mảng sản phẩm liên quan vào đối tượng sản phẩm trả về.
        $product->related = $relatedProducts;

        // Trả về dữ liệu thành công dưới dạng JSON.
        return response()->json($product);
    }
}
