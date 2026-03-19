<x-mail::message>
# Xin chào {{ $order->user->name }},

**Đơn hàng của bạn đã được xác nhận!**

---

### Thông tin đơn hàng
- **Mã đơn hàng:** #{{ $order->id }}
- **Ngày đặt:** {{ $order->created_at->format('d/m/Y H:i') }}
- **Tổng tiền:** {{ number_format($order->total_amount, 0, ',', '.') }}đ
- **Phí ship:** {{ number_format($order->shipping_cost, 0, ',', '.') }}đ
- **Phương thức thanh toán:** 
  @if($order->payment_method === 'paypal') PayPal
  @elseif($order->payment_method === 'momo') Momo
  @elseif($order->payment_method === 'zalopay') ZaloPay
  @else {{ ucfirst($order->payment_method) }}
  @endif

---

### Sản phẩm đã đặt
@foreach($order->items as $item)
- {{ $item->quantity }}x **{{ $item->product?->name ?? 'Sản phẩm đã xóa' }}**  
  Giá: {{ number_format($item->price, 0, ',', '.') }}đ  
  → **{{ number_format($item->price * $item->quantity, 0, ',', '.') }}đ**
@endforeach

---

<x-mail::button :url="route('order.showuser', $order->id)">
Xem chi tiết đơn hàng
</x-mail::button>

Cảm ơn bạn đã tin tưởng **{{ config('app.name') }}**!  
Nếu có thắc mắc, vui lòng phản hồi email này.

Thân mến,  
**{{ config('app.name') }}**
</x-mail::message>