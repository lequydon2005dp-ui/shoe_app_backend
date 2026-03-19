<x-mail::message>
# Xin chào {{ $order->user->name ?? 'Khách hàng' }},

Trạng thái đơn hàng **#{{ $order->id }}** của bạn vừa được **cập nhật**.

---

### Thông tin đơn hàng
- **Mã đơn hàng:** #{{ $order->id }}
- **Ngày đặt hàng:** {{ $order->created_at->format('d/m/Y H:i') }}
- **Trạng thái mới:**
  @switch($order->status)
    @case('pending')
      Chờ xử lý
      @break
    @case('processing')
      Đã xác thực
      @break
    @case('shipping')
      Đang giao hàng
      @break
    @case('delivered')
      Đã giao hàng
      @break
    @case('completed')
      Hoàn tất
      @break
    @case('cancelled')
      Đã hủy đơn
      @break
    @default
      {{ ucfirst($order->status) }}
  @endswitch

---

<x-mail::button :url="route('order.showuser', $order->id)">
Xem chi tiết đơn hàng
</x-mail::button>

Cảm ơn bạn đã tin tưởng và mua hàng tại **{{ config('app.name') }}**!  
Nếu bạn có bất kỳ thắc mắc nào, hãy phản hồi email này để được hỗ trợ.

Thân mến,  
**Đội ngũ {{ config('app.name') }}**
</x-mail::message>