@extends('layout')

@section('content')
<section class="p-6 overflow-auto flex-1">
    <div class="bg-white rounded-lg shadow-sm p-4">
        <h2 class="text-lg font-semibold mb-4">Chi tiết đơn hàng</h2>

        @php
        $orderdetails = $order->orderdetails ?? $order->items;
        $user = $order->user;
        @endphp

        {{-- Hiển thị thông báo --}}
        @if(session('success'))
        <div class="bg-green-100 text-green-700 border border-green-400 rounded p-2 mb-4 text-sm">
            ✅ {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="bg-red-100 text-red-700 border border-red-400 rounded p-2 mb-4 text-sm">
            ⚠️ {{ session('error') }}
        </div>
        @endif

        {{-- Thông tin khách hàng --}}
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6 text-sm">
            <div>Họ tên: <strong>{{ $user->name }}</strong></div>
            <div>Email: <strong>{{ $user->email }}</strong></div>
            <div>Điện thoại: <strong>{{ $user->phone }}</strong></div>
            <div>Địa chỉ: <strong>{{ $user->address }}</strong></div>
            <div>Ngày đặt hàng: <strong>{{ $order->created_at->format('d/m/Y H:i') }}</strong></div>
            <div>Trạng thái:
                <strong class="
                    @if($order->status == 'delivered') text-green-600
                    @elseif($order->status == 'cancelled') text-red-600
                    @elseif($order->status == 'completed') text-fuchsia-600
                    @elseif($order->status == 'processing') text-blue-600
                    @elseif($order->status == 'completed') text-pink-600s
                    @else text-yellow-600
                    @endif
                ">
                    {{ ucfirst($order->status) }}
                </strong>
            </div>
        </div>

        {{-- Bảng chi tiết sản phẩm --}}
        <table class="min-w-full divide-y divide-gray-200 text-sm mb-6">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 border text-center w-10 font-medium">#</th>
                    <th class="px-4 py-2 border text-left font-medium">Sản phẩm</th>
                    <th class="px-4 py-2 border text-center font-medium">Số lượng</th>
                    <th class="px-4 py-2 border text-right font-medium">Giá</th>
                    <th class="px-4 py-2 border text-right font-medium">Thành tiền</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($orderdetails as $index => $item)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 border text-center">{{ $index + 1 }}</td>
                    <td class="px-4 py-3 border">{{ $item->product->name ?? 'Sản phẩm đã xóa' }}</td>
                    <td class="px-4 py-3 border text-center">{{ $item->quantity }}</td>
                    <td class="px-4 py-3 border text-right">{{ number_format($item->price, 0, ',', '.') }}₫</td>
                    <td class="px-4 py-3 border text-right">
                        {{ number_format($item->price * $item->quantity, 0, ',', '.') }}₫
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-gray-500 py-3">Không có sản phẩm nào trong đơn hàng.</td>
                </tr>
                @endforelse
            </tbody>
            <tfoot class="bg-gray-50">
                <tr>
                    <td colspan="4" class="px-4 py-3 border text-right font-semibold">Tổng cộng:</td>
                    <td class="px-4 py-3 border text-right font-semibold text-red-600">
                        {{ number_format($orderdetails->sum(fn($item) => $item->price * $item->quantity), 0, ',', '.') }}₫
                    </td>
                </tr>
            </tfoot>
        </table>

        {{-- Form cập nhật trạng thái --}}
        <form method="POST" action="{{ route('order.updateStatus', $order->id) }}" class="flex items-center gap-4 mb-6">
            @csrf
            @method('PUT')
            <label for="status" class="font-medium text-sm">Cập nhật trạng thái:</label>
            <select name="status" id="status" class="border border-gray-300 rounded px-3 py-1 text-sm">
                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Đã xác thực</option>
                <option value="shipping" {{ $order->status == 'shipping' ? 'selected' : '' }}>Đang giao hàng</option>
                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Đã giao hàng</option>
                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Hoàn tất</option>
                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
            </select>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1 rounded text-sm">
                Lưu
            </button>
        </form>

        {{-- Nút quay lại --}}
        <div>
            <a href="{{ route('order.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-sm rounded">
                ← Quay lại danh sách
            </a>
        </div>
    </div>
</section>
@endsection