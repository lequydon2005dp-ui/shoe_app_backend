<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class FavoriteController extends Controller
{
    /**
     * Hiển thị danh sách sản phẩm yêu thích của user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        /** @var \App\Models\User $user */ // <-- THÊM DÒNG NÀY
        $user = Auth::user();
        $favorites = $user->favorites()
            ->with('product') // Tải thêm images để hiển thị
            ->get()
            ->pluck('product')
            ->filter(); // Lọc ra các product null (nếu có)

        return response()->json([
            'success' => true,
            'favorites' => $favorites->values(),
            'total' => $favorites->count()
        ]);
    }

    /**
     * Thêm sản phẩm vào danh sách yêu thích
     * (Hàm này có thể không cần nữa nếu chỉ dùng 'toggle')
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:product,id',
        ]);

        $userId = Auth::id();
        $productId = $request->product_id;

        // Dùng firstOrCreate để tránh lỗi trùng lặp
        $favorite = Favorite::firstOrCreate(
            [
                'user_id' => $userId,
                'product_id' => $productId
            ]
        );

        // Kiểm tra xem nó có vừa được tạo hay đã tồn tại
        if (!$favorite->wasRecentlyCreated) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm này đã có trong danh sách yêu thích.',
            ], 409); // 409 Conflict
        }

        $favorite->load('product');

        return response()->json([
            'success' => true,
            'message' => 'Đã thêm vào yêu thích',
            'favorite' => $favorite->product
        ], 201);
    }

    /**
     * Xóa sản phẩm khỏi danh sách yêu thích
     * (Hàm này có thể không cần nữa nếu chỉ dùng 'toggle')
     * @param int $product_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($product_id)
    {
        $product_id = (int) $product_id;

        $deleted = Favorite::where('user_id', Auth::id())
            ->where('product_id', $product_id)
            ->delete();

        if (!$deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm không tồn tại trong danh sách yêu thích.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa khỏi danh sách yêu thích.'
        ]);
    }

    /**
     * Toggle yêu thích (thêm hoặc xóa)
     * Đây là hàm chính mà App (React Native) đang gọi
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:product,id',
        ]);

        $userId = Auth::id();
        $productId = $request->product_id;

        $favorite = Favorite::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($favorite) {
            // Đang có → xóa
            $favorite->delete();
            return response()->json([
                'success' => true,
                'action' => 'removed',
                'message' => 'Đã xóa khỏi yêu thích'
            ]);
        } else {
            // Chưa có → thêm
            $newFavorite = Favorite::create([
                'user_id' => $userId,
                'product_id' => $productId,
            ]);
            
            // Tải product relationship (với cả images) để trả về cho App
            $newFavorite->load('product');

            return response()->json([
                'success' => true,
                'action' => 'added',
                'message' => 'Đã thêm vào yêu thích',
                'favorite' => $newFavorite->product // Trả về product object
            ], 201);
        }
    }

    // ❗❗❗ [ĐÃ THÊM] HÀM ĐỒNG BỘ ❗❗❗
    /**
     * Đồng bộ danh sách yêu thích từ offline (local) lên server.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sync(Request $request)
    {
        // Xác thực dữ liệu đầu vào (phải là một mảng)
        $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'integer|exists:product,id' // Đảm bảo product có tồn tại
        ]);

        $user = Auth::user();
        $productIds = $request->input('product_ids');
        $syncedCount = 0;

        foreach ($productIds as $productId) {
            // Sử dụng firstOrCreate để tránh thêm trùng lặp
            // Nó sẽ tìm, nếu không thấy, nó sẽ tạo mới
            $favorite = Favorite::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'product_id' => $productId
                ]
            );

            // Kiểm tra xem bản ghi này có phải vừa được tạo mới không
            if ($favorite->wasRecentlyCreated) {
                $syncedCount++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Đồng bộ hoàn tất.',
            'total_synced' => $syncedCount,
            'total_added' => count($productIds)
        ], 200);
    }
}