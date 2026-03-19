<x-mail::message>
# Yêu cầu đặt lại mật khẩu

Chúng tôi nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn.

<x-mail::button :url="$url">
Đặt lại mật khẩu ngay
</x-mail::button>

**Liên kết sẽ hết hạn sau 60 phút.**

Nếu bạn **không yêu cầu**, vui lòng bỏ qua email này.

Thân mến,  
**{{ config('app.name') }}**
</x-mail::message>