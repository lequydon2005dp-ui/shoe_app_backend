<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập – {{ config('app.name') }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 20px; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        .container { max-width: 420px; width: 100%; background: white; border-radius: 20px; overflow: hidden; box-shadow: 0 15px 35px rgba(0,0,0,0.1); }
        .header { background: #667eea; color: white; padding: 35px 30px; text-align: center; }
        .header h1 { font-size: 26px; margin: 0; font-weight: 600; }
        .body { padding: 35px 30px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; font-weight: 600; color: #444; margin-bottom: 8px; font-size: 14px; }
        input { width: 100%; padding: 14px 16px; border: 1px solid #ddd; border-radius: 10px; font-size: 16px; transition: all 0.3s; }
        input:focus { outline: none; border-color: #667eea; box-shadow: 0 0 0 3px rgba(102,126,234,0.15); }
        .checkbox { display: flex; align-items: center; font-size: 14px; color: #555; }
        .checkbox input { width: auto; margin-right: 8px; }
        button { width: 100%; padding: 14px; background: #667eea; color: white; border: none; border-radius: 10px; font-size: 16px; font-weight: 600; cursor: pointer; transition: 0.3s; margin-top: 10px; }
        button:hover { background: #5a6fd8; transform: translateY(-1px); }
        .footer { text-align: center; padding: 20px; font-size: 13px; color: #888; background: #f8f9fa; }
        .alert { padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-size: 14px; }
        .alert-success { background:#d4edda; color:#155724; border:1px solid #c3e6cb; }
        .alert-error { background:#f8d7da; color:#721c24; border:1px solid #f5c6cb; }
        .link { color: #667eea; text-decoration: none; font-weight: 600; }
        .link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Đăng nhập</h1>
        </div>
        <div class="body">

            <!-- THÔNG BÁO THÀNH CÔNG (từ đặt lại mật khẩu) -->
            @if(session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <!-- LỖI ĐĂNG NHẬP -->
            @if($errors->has('email'))
                <div class="alert alert-error">
                    {{ $errors->first('email') }}
                </div>
            @endif

            <!-- FORM ĐĂNG NHẬP -->
            <form method="POST" action="{{ route('login.post') }}">
                @csrf

                <div class="form-group">
                    <label for="email">Email</label>
                    <input 
                        type="email" 
                        id="email"
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        autofocus 
                        placeholder="nhập email của bạn"
                    >
                </div>

                <div class="form-group">
                    <label for="password">Mật khẩu</label>
                    <input 
                        type="password" 
                        id="password"
                        name="password" 
                        required 
                        placeholder="nhập mật khẩu"
                    >
                </div>

                <div class="checkbox">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember">Ghi nhớ đăng nhập</label>
                </div>

                <button type="submit">Đăng nhập ngay</button>
            </form>

            <div style="text-align: center; margin-top: 20px;">
                <a href="/forgot-password" class="link">Quên mật khẩu?</a>
            </div>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} <strong>{{ config('app.name') }}</strong>. Hệ thống quản trị.</p>
        </div>
    </div>
</body>
</html>