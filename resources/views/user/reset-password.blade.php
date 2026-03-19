<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt lại mật khẩu – {{ config('app.name') }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', Arial, sans-serif; 
            background: #f8f9fa; 
            padding: 20px; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            min-height: 100vh; 
        }
        .container { 
            max-width: 420px; 
            width: 100%; 
            background: white; 
            border-radius: 20px; 
            overflow: hidden; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.08); 
        }
        .header { 
            background: #667eea; 
            color: white; 
            padding: 30px; 
            text-align: center; 
        }
        .header h1 { 
            font-size: 24px; 
            margin: 0; 
            font-weight: 600; 
        }
        .body { 
            padding: 30px; 
        }
        .form-group { 
            margin-bottom: 20px; 
        }
        label { 
            display: block; 
            font-weight: 600; 
            color: #444; 
            margin-bottom: 8px; 
            font-size: 14px; 
        }
        input { 
            width: 100%; 
            padding: 14px 16px; 
            border: 1px solid #ddd; 
            border-radius: 12px; 
            font-size: 16px; 
            transition: all 0.3s; 
        }
        input::placeholder { 
            color: #aaa; 
            font-size: 14px; 
        }
        input:focus { 
            outline: none; 
            border-color: #667eea; 
            box-shadow: 0 0 0 3px rgba(102,126,234,0.15); 
        }
        button { 
            width: 100%; 
            padding: 14px; 
            background: #667eea; 
            color: white; 
            border: none; 
            border-radius: 12px; 
            font-size: 16px; 
            font-weight: 600; 
            cursor: pointer; 
            transition: 0.3s; 
            margin-top: 10px; 
        }
        button:hover { 
            background: #5a6fd8; 
            transform: translateY(-1px); 
        }
        .footer { 
            text-align: center; 
            padding: 20px; 
            font-size: 13px; 
            color: #888; 
            background: #f8f9fa; 
            border-top: 1px solid #eee; 
        }
        .alert { 
            padding: 12px 16px; 
            border-radius: 12px; 
            margin-bottom: 20px; 
            font-size: 14px; 
        }
        .alert-success { 
            background:#d4edda; 
            color:#155724; 
            border:1px solid #c3e6cb; 
        }
        .alert-error { 
            background:#f8d7da; 
            color:#721c24; 
            border:1px solid #f5c6cb; 
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Đặt lại mật khẩu</h1>
        </div>
        <div class="body">

            <!-- THÔNG BÁO THÀNH CÔNG -->
            @if(session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <!-- LỖI VALIDATE -->
            @if($errors->any())
                <div class="alert alert-error">
                    @foreach($errors->all() as $error)
                        {{ $error }}<br>
                    @endforeach
                </div>
            @endif

            <!-- CHỈ 1 FORM DUY NHẤT -->
            <form action="{{ route('password.update') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">

                <div class="form-group">
                    <label for="password">Mật khẩu mới</label>
                    <input 
                        type="password" 
                        id="password"
                        name="password" 
                        required 
                        minlength="6" 
                        placeholder="Ít nhất 6 ký tự"
                    >
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Xác nhận mật khẩu</label>
                    <input 
                        type="password" 
                        id="password_confirmation"
                        name="password_confirmation" 
                        required 
                        minlength="6" 
                        placeholder="Nhập lại mật khẩu"
                    >
                </div>

                <button type="submit">Xác nhận</button>
            </form>
        </div>
        <div class="footer">
            <p><strong>{{ config('app.name') }}</strong> – Hệ thống thương mại điện tử</p>
            <p>Liên kết hết hạn sau 60 phút.</p>
        </div>
    </div>
</body>
</html>