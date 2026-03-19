<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết đơn hàng #{{ $order->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            line-height: 1.6;
        }

        .container {
            max-width: 700px;
            margin: 30px auto;
            background: #fff;
            border-radius: 12px;
            padding: 20px;
        }

        .header {
            background: #667eea;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 12px 12px 0 0;
        }

        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 14px;
            opacity: 0.9;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-top: 10px;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-processing {
            background: #d1ecf1;
            color: #0c5460;
        }

        .status-completed {
            background: #d4edda;
            color: #155724;
        }

        .status-delivered {
            background: #cff4fc;
            color: #055160;
        }

        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin: 20px 0;
            padding: 15px;
            background: #f1f3f5;
            border-radius: 8px;
        }

        .info-item strong {
            display: block;
            font-size: 12px;
            color: #6c757d;
            margin-bottom: 3px;
        }

        .info-item span {
            font-size: 14px;
            font-weight: 500;
            color: #212529;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .items-table th,
        .items-table td {
            padding: 10px;
            border-bottom: 1px solid #e9ecef;
            text-align: left;
        }

        .items-table th {
            background: #f1f3f5;
            font-weight: 600;
            font-size: 13px;
        }

        .product-info {
            display: flex;
            align-items: center;
        }

        .product-info img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 6px;
            margin-right: 10px;
        }

        .text-right {
            text-align: right;
        }

        .total-row {
            font-weight: 700;
            background: #f8f9fa;
        }

        .footer {
            background: #f8f9fa;
            padding: 15px;
            text-align: center;
            font-size: 12px;
            color: #6c757d;
            border-top: 1px solid #dee2e6;
            margin-top: 20px;
        }

        .footer a {
            color: #667eea;
            text-decoration: none;
        }

        @media (max-width: 600px) {
            .info-grid {
                grid-template-columns: 1fr;
            }

            .header h1 {
                font-size: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>Đơn hàng #{{ $order->id }}</h1>
            <p>Cập nhật lúc: {{ $order->updated_at->format('d/m/Y H:i') }}</p>
            <div class="status-badge status-{{ $order->status }}">
                @switch($order->status)
                @case('pending') ⏳ Chờ xử lý @break
                @case('processing') 🔍 Đã xác thực @break
                @case('shipping') 🚚 Đang giao hàng @break
                @case('delivered') ✅ Đã giao hàng @break
                @case('completed')🏁 Hoàn tất@break
                @case('cancelled') ❌ Đã hủy @break
                @default {{ ucfirst($order->status) }}
                @endswitch
            </div>
        </div>

        <!-- Thông tin chung -->
        <div class="info-grid">
            <div class="info-item"><strong>Khách hàng</strong><span>{{ $order->user->name }}</span></div>
            <div class="info-item"><strong>Email</strong><span>{{ $order->email }}</span></div>
            <div class="info-item"><strong>Số điện thoại</strong><span>{{ $order->phone }}</span></div>
            <div class="info-item"><strong>Ngày đặt</strong><span>{{ $order->created_at->format('d/m/Y H:i') }}</span></div>
            <div class="info-item"><strong>Địa chỉ giao</strong><span>{{ $order->address }}</span></div>
            <div class="info-item"><strong>Phương thức thanh toán</strong>
                <span>
                    @if($order->payment_method === 'paypal') PayPal
                    @elseif($order->payment_method === 'momo') Momo
                    @elseif($order->payment_method === 'zalopay') ZaloPay
                    @else {{ ucfirst($order->payment_method) }}
                    @endif
                </span>
            </div>
        </div>

        <!-- Danh sách sản phẩm -->
        <table class="items-table">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Số lượng</th>
                    <th class="text-right">Đơn giá</th>
                    <th class="text-right">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>
                        <div class="product-info">
                            @if($item->product && $item->product->image_url)
                            <img src="{{ asset('storage/' . $item->product->image_url) }}" alt="{{ $item->product->name }}">
                            @endif
                            <div>{{ $item->product?->name ?? 'Sản phẩm đã xóa' }}</div>
                        </div>
                    </td>
                    <td>{{ $item->quantity }}</td>
                    <td class="text-right">{{ number_format($item->price, 0, ',', '.') }}đ</td>
                    <td class="text-right">{{ number_format($item->price * $item->quantity, 0, ',', '.') }}đ</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Tổng tiền -->
        <table class="items-table">
            <tr>
                <td colspan="3" class="text-right"><strong>Tạm tính</strong></td>
                <td class="text-right">{{ number_format($order->total_amount - $order->shipping_cost, 0, ',', '.') }}đ</td>
            </tr>
            <tr>
                <td colspan="3" class="text-right"><strong>Phí vận chuyển</strong></td>
                <td class="text-right">{{ number_format($order->shipping_cost, 0, ',', '.') }}đ</td>
            </tr>
            <tr class="total-row">
                <td colspan="3" class="text-right"><strong>TỔNG CỘNG</strong></td>
                <td class="text-right" style="color: #d63384; font-size: 16px;">
                    <strong>{{ number_format($order->total_amount, 0, ',', '.') }}đ</strong>
                </td>
            </tr>
        </table>

        <!-- Footer -->
        <div class="footer">
            <p><strong>{{ config('app.name') }}</strong> – Cảm ơn bạn đã mua sắm!</p>
            <p>Bạn nhận được email này vì đã đặt hàng tại hệ thống của chúng tôi.</p>
            <p>Cần hỗ trợ? Liên hệ: <a href="mailto:support@{{ str_replace(' ', '', strtolower(config('app.name'))) }}.com">support@{{ str_replace(' ', '', strtolower(config('app.name'))) }}.com</a></p>
            <p style="margin-top: 10px; color: #999;">© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>

</html>