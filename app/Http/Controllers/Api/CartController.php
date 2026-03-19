<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart; // Đảm bảo bạn đã import Cart model
use App\Models\User; // Đảm bảo bạn đã import User model
// use App\Models\Product; // Import Product model nếu bạn cần truy vấn trực tiếp

class CartController extends Controller
{
    /**
     * Helper function: Lấy và định dạng giỏ hàng của người dùng.
     * Trả về một mảng đã được định dạng, sẵn sàng cho client.
     */
    private function getFormattedCart(User $user)
    {
        // SỬA LỖI 1: Xóa '.images' đi. Chúng ta không cần nó nữa.
        $cartItems = $user->cart()->with('product')->get();

        // Map (biến đổi) dữ liệu
        return $cartItems->map(function ($cartItem) {
            if (!$cartItem->product) {
                return null;
            }

            // SỬA LỖI 2: Lấy ảnh trực tiếp từ cột của product
            //
            // HÃY KIỂM TRA DATABASE CỦA BẠN (bảng 'products')
            // và thay 'image_url' bằng tên cột ảnh thật của bạn 
            // (có thể là 'image', 'thumbnail', 'product_image', ...)
            $imageUrl = $cartItem->product->image_url;
            // Ví dụ: $imageUrl = $cartItem->product->image;

            return [
                'id' => $cartItem->product->id,
                'name' => $cartItem->product->name,
                'price' => $cartItem->product->price,
                'price_discount' => $cartItem->product->price_discount,

                // Trả về ảnh đã lấy được
                'image_url' => $imageUrl,

                'quantity' => $cartItem->quantity,
                'selectedSize' => $cartItem->size,
            ];
        })->filter()->values();
    }

    /**
     * [GET] /api/cart
     * Lấy toàn bộ giỏ hàng của người dùng.
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $formattedCart = $this->getFormattedCart($user);

        // Trả về giỏ hàng đã được định dạng
        return response()->json($formattedCart);
    }

    /**
     * [POST] /api/cart/add
     * Thêm 1 sản phẩm vào giỏ hàng (dựa trên product_id và size).
     * Nếu đã tồn tại, tăng số lượng lên.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // SỬA: 'exists:products,id' (dựa trên lỗi trước đó của bạn)
            'product_id' => 'required|exists:product,id',
            'quantity' => 'required|integer|min:1',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Tìm item trong giỏ hàng dựa trên product_id VÀ size
        $cartItem = $user->cart()->firstOrNew([
            'product_id' => $validated['product_id'],
        ]);

        // Tăng số lượng (thay vì set cứng)
        $cartItem->quantity += $validated['quantity'];
        $cartItem->save();

        // Trả về TOÀN BỘ giỏ hàng đã cập nhật
        return response()->json($this->getFormattedCart($user), 201);
    }

    /**
     * [POST] /api/cart/decrease
     * Giảm số lượng của 1 item (dựa trên product_id và size).
     * Nếu số lượng về 0, xóa item.
     */
    public function decrease(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:product,id',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $cartItem = $user->cart()->where([
            'product_id' => $validated['product_id'],
        ])->first();

        if ($cartItem) {
            $cartItem->quantity -= 1; // Giảm đi 1
            if ($cartItem->quantity <= 0) {
                $cartItem->delete(); // Xóa nếu hết
            } else {
                $cartItem->save(); // Lưu lại
            }
        }

        // Trả về TOÀN BỘ giỏ hàng đã cập nhật
        return response()->json($this->getFormattedCart($user));
    }

    /**
     * [POST] /api/cart/remove
     * Xóa hoàn toàn 1 item khỏi giỏ hàng (dựa trên product_id và size).
     */
    public function remove(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:product,id',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Tìm và xóa item
        $user->cart()->where([
            'product_id' => $validated['product_id'],
        ])->delete();

        // Trả về TOÀN BỘ giỏ hàng đã cập nhật
        return response()->json($this->getFormattedCart($user));
    }
}
