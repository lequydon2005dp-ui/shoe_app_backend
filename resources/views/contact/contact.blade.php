{{-- resources/views/contact.blade.php --}}
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liên hệ hỗ trợ - {{ config('app.name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            line-height: 1.6;
        }

        .container {
            max-width: 800px;
            margin: 40px auto;
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }

        .header {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }

        .header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .header p {
            font-size: 16px;
            opacity: 0.9;
        }

        .content {
            padding: 40px 30px;
        }

        .section {
            margin-bottom: 32px;
        }

        .section h2 {
            font-size: 20px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 16px;
        }

        .info-card {
            background: #f8f9ff;
            padding: 20px;
            border-radius: 12px;
            border-left: 4px solid #6366f1;
        }

        .info-card h3 {
            font-size: 16px;
            font-weight: 600;
            color: #4f46e5;
            margin-bottom: 8px;
        }

        .info-card p,
        .info-card a {
            font-size: 15px;
            color: #555;
            text-decoration: none;
        }

        .info-card a:hover {
            color: #4f46e5;
            text-decoration: underline;
        }

        .hours {
            font-size: 14px;
            color: #6b7280;
            margin-top: 8px;
            font-style: italic;
        }

        .faq {
            background: #f1f5f9;
            padding: 20px;
            border-radius: 12px;
            margin-top: 16px;
        }

        .faq h3 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 12px;
            color: #1f2937;
        }

        .faq ul {
            list-style: none;
            padding-left: 0;
        }

        .faq li {
            padding: 8px 0;
            border-bottom: 1px dashed #cbd5e1;
            font-size: 14.5px;
        }

        .faq li:last-child {
            border-bottom: none;
        }

        .footer {
            text-align: center;
            padding: 24px;
            background: #f8f9fa;
            color: #64748b;
            font-size: 14px;
            border-top: 1px solid #e2e8f0;
        }

        .social-links {
            margin: 16px 0;
        }

        .social-links a {
            display: inline-block;
            margin: 0 8px;
            color: #6366f1;
            font-weight: 500;
            text-decoration: none;
        }

        .social-links a:hover {
            text-decoration: underline;
        }

        @media (max-width: 640px) {
            .container {
                margin: 20px 16px;
            }

            .header {
                padding: 30px 20px;
            }

            .content {
                padding: 24px 20px;
            }

            .header h1 {
                font-size: 24px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>Liên hệ hỗ trợ</h1>
            <p>Chúng tôi luôn sẵn sàng hỗ trợ bạn 24/7</p>
        </div>

        <!-- Content -->
        <div class="content">

            <!-- Thông tin liên hệ -->
            <div class="section">
                <h2>
                    <!-- Envelope -->
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    Thông tin liên hệ
                </h2>
                <div class="info-grid">
                    <div class="info-card">
                        <h3>Email hỗ trợ</h3>
                        <p><a href="mailto:lequydon2005dp@gmail.com">lequydon2005dp@gmail.com</a></p>
                        <div class="hours">Phản hồi trong vòng 2 giờ</div>
                    </div>
                    <div class="info-card">
                        <h3>Hotline</h3>
                        <p><a href="tel:19006868">0868327457</a></p>
                        <div class="hours">8:00 - 22:00 hàng ngày</div>
                    </div>
                    <div class="info-card">
                        <h3>Zalo / Chat</h3>
                        <p><a href="https://id.zalo.me/account?continue=https%3A%2F%2Fchat.zalo.me%2F" target="_blank">Chat ngay với CSKH</a></p>
                        <div class="hours">Hoạt động 24/7</div>
                    </div>
                    <div class="info-card">
                        <h3>Địa chỉ</h3>
                        <p>Tăng Nhơn Phú, Phước Long B, Quận 9, TP. Thủ Đức</p>
                    </div>
                </div>
            </div>

            <!-- FAQ nhanh -->
            <div class="section">
                <h2>
                    <!-- Question Mark Circle -->
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Câu hỏi thường gặp
                </h2>
                <div class="faq">
                    <h3>Khi hủy đơn hàng, tôi có được hoàn tiền không?</h3>
                    <ul>
                        <li>Đơn <strong>chưa xử lý</strong>: Hoàn tiền 100% trong 24h</li>
                        <li>Đơn <strong>đang xử lý</strong>: Hoàn tiền sau khi xác nhận kho</li>
                        <li>Đơn <strong>đã giao</strong>: Không hỗ trợ hủy</li>
                    </ul>
                </div>
            </div>

            <!-- Gợi ý hành động -->
            <div class="section">
                <h2>
                    <!-- Chat Bubble -->
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    Cần hỗ trợ ngay?
                </h2>
                <p style="font-size: 15.5px; color: #374151; margin-top: 12px;">
                    Gửi email đến <strong>lequydon2005dp@gmail.com</strong> hoặc gọi <strong>0868327457</strong> để được tư vấn nhanh nhất.
                </p>
            </div>

        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>{{ config('app.name') }}</strong> – Đồng hành cùng mọi đơn hàng của bạn</p>
            <div class="social-links">
                <a href="https://www.facebook.com/?locale=vi_VN">Facebook</a> |
                <a href="https://id.zalo.me/account?continue=https%3A%2F%2Fchat.zalo.me%2F">Zalo</a> |
                <a href="https://www.instagram.com/">Instagram</a>
            </div>
            <p style="margin-top: 12px; font-size: 13px;">
                © {{ date('Y') }} {{ config('app.name') }}. Tất cả quyền được bảo lưu.
            </p>
        </div>
    </div>
</body>

</html>