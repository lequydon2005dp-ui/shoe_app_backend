<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\OrderCancelled;
use App\Mail\OrderSuccessMail;
use App\Models\Cart;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Lưu một đơn hàng mới.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'payment_method' => 'required|string|max:50',
            'shipping_cost' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'items' => 'required|array', // 'items' là một mảng
            'items.*.product_id' => 'required|integer|exists:product,id', // Mỗi item phải có product_id
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()->all() // Gửi mảng lỗi
            ], 422);
        }

        $user = Auth::user();
        $data = $validator->validated();

        // Sử dụng Transaction để đảm bảo tính toàn vẹn dữ liệu
        // Nếu một trong các lệnh thất bại, tất cả sẽ bị rollback
        try {
            DB::beginTransaction();
            $totalAmount = collect($data['items'])->sum(function ($item) {
                return $item['quantity'] * $item['price'];
            });
            // 1. Tạo Đơn hàng (Order)
            $order = Order::create([
                'user_id' => $user->id,
                'name' => $user->name,
                'address' => $data['address'],
                'phone' => $data['phone'],
                'email' => $data['email'],
                'payment_method' => $data['payment_method'],
                'shipping_cost' => $data['shipping_cost'],
                'total_amount' => $totalAmount + $data['shipping_cost'],
                'status' => 'pending', // Trạng thái ban đầu
            ]);

            // 2. Tạo các chi tiết đơn hàng (Order Items)
            foreach ($data['items'] as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'], // Giá tại thời điểm mua
                ]);
            }

            // 3. (QUAN TRỌNG) Xóa giỏ hàng của người dùng
            // Giả sử bạn có model Cart liên kết với user_id
            Cart::where('user_id', $user->id)->delete();
            Notification::create([
                'user_id' => Auth::id(),
                'title' => 'Đặt hàng thành công!',
                'message' => "Đơn hàng #{$order->id} đã được đặt thành công.",
                'data' => json_encode([
                    'order_id' => $order->id,
                    'total' => $order->total_amount,
                    'status' => $order->status,
                    'created_at' => $order->created_at->format('d/m/Y H:i'),
                ]),
            ]);
            DB::commit();
            try {
                Mail::to($order->email)->send(new OrderSuccessMail($order));
            } catch (\Exception $mailError) {
                Log::warning('Gửi email xác nhận thất bại (đơn hàng vẫn thành công): ' . $mailError->getMessage());
                // Không rollback → đơn hàng vẫn OK
            }

            return response()->json([
                'success' => true,
                'message' => 'Đặt hàng thành công! Email xác nhận đã được gửi.',
                'order_id' => $order->id,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi đặt hàng: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Lỗi hệ thống: ' . $e->getMessage(),
            ], 500);
        }
    }
    public function list(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                // Điều này không bao giờ xảy ra nếu route nằm trong 'auth:sanctum'
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            // Lấy đơn hàng, sắp xếp mới nhất, và eager load items (và thông tin product)
            $orders = Order::where('user_id', $user->id)
                ->with('items.product') // Tải 'items' VÀ 'product' trong mỗi 'item'
                ->orderBy('created_at', 'desc') // Mới nhất lên đầu
                ->get();

            return response()->json(['orders' => $orders], 200);
        } catch (\Exception $e) {
            Log::error('Lỗi khi lấy danh sách đơn hàng: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Không thể lấy danh sách đơn hàng.'
            ], 500);
        }
    }
    public function show($id)
    {
        $user = Auth::user();

        $order = Order::where('user_id', $user->id)
            ->where('id', $id)
            ->with('items.product')
            ->first();

        if (!$order) {
            return response()->json(['message' => 'Đơn hàng không tồn tại'], 404);
        }

        return response()->json(['order' => $order], 200);
    }
    public function cancel(Request $request, $orderId)
    {
        $request->validate(['reason' => 'required|string|max:500']);

        $order = Order::findOrFail($orderId);

        if (!in_array($order->status, ['pending', 'processing'])) {
            return response()->json([
                'message' => 'Chỉ có thể hủy đơn hàng ở trạng thái chờ xử lý hoặc đang xử lý.'
            ], 400);
        }

        $order->update([
            'status' => 'cancelled',
            'cancel_reason' => $request->reason,
            'cancelled_at' => now(),
        ]);
        Notification::create([
            'user_id' => Auth::id(),
            'title' => 'Hủy đơn hàng thành công!',
            'message' => "Đơn hàng #{$order->id} đã được hủy thành công.",
            'data' => json_encode([
                'order_id' => $order->id,
                'total' => $order->total_amount,
                'status' => $order->status,
                'created_at' => $order->created_at->format('d/m/Y H:i'),
            ]),
        ]);
        try {
            Mail::to($order->email)->send(new OrderCancelled($order));
        } catch (\Exception $e) {
            Log::error('Gửi mail hủy đơn thất bại: ' . $e->getMessage());
        }

        return response()->json([
            'message' => 'Đơn hàng đã được hủy thành công.',
            'order' => $order
        ]);
    }
}
