<x-mail::message>
# Đơn hàng của bạn đã bị hủy

Kính gửi **{{ $order->name ?? 'Quý khách' }},

Chúng tôi rất tiếc phải thông báo rằng **đơn hàng #{{ $order->id }}** đã được **hủy thành công** theo yêu cầu của bạn.

### Thông tin chi tiết:
- **Thời gian hủy:** {{ \Carbon\Carbon::parse($order->cancelled_at)->format('H:i, d/m/Y') }}
- **Lý do hủy:**  
  > {{ $order->cancel_reason }}

---

### Tóm tắt đơn hàng:
| Mục                  | Nội dung                          |
|----------------------|-----------------------------------|
| Ngày đặt hàng        | {{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }} |
| Địa chỉ giao hàng    | {{ $order->address }}             |
| Số điện thoại        | {{ $order->phone }}               |             |
| Tổng tiền            | **{{ number_format($order->total_amount) }} ₫** |

---

### Chi tiết sản phẩm trong đơn hàng:

<x-mail::table>
| Sản phẩm | Số lượng | Đơn giá | Thành tiền |
|---------|----------|---------|------------|
@foreach($order->items as $item)
| {{ $item->product_name ?? 'Sản phẩm' }} | {{ $item->quantity }} | {{ number_format($item->price) }} ₫ | **{{ number_format($item->quantity * $item->price) }} ₫** |
@endforeach
</x-mail::table>

---

Nếu bạn cần hỗ trợ thêm hoặc muốn đặt lại đơn hàng, vui lòng liên hệ với chúng tôi qua email hoặc hotline.

<x-mail::button :url="route('order.contact')">
Liên hệ hỗ trợ
</x-mail::button>

Cảm ơn bạn đã tin tưởng sử dụng dịch vụ của chúng tôi!

Trân trọng,<br>
<strong>{{ config('app.name') }}</strong>
</x-mail::message>