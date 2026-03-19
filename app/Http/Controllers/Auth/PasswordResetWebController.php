<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Carbon\Carbon;

class PasswordResetWebController extends Controller
{
    // HIỂN THỊ TRANG ĐỔI MẬT KHẨU
    public function show(Request $request, $token)
    {
        $email = $request->query('email');

        // KIỂM TRA TOKEN TRONG DB
        $reset = DB::table('password_resets')
            ->where('token', $token)
            ->where('email', $email)
            ->first();

        // NẾU KHÔNG TỒN TẠI HOẶC HẾT HẠN
        if (!$reset || Carbon::parse($reset->created_at)->addMinutes(60)->isPast()) {
            return redirect('login')->with('error', 'Liên kết không hợp lệ hoặc đã hết hạn.');
        }

        // TRUYỀN ĐÚNG token + email VÀO VIEW
        return view('user.reset-password', [
            'token' => $token,
            'email' => $email,
        ]);
    }

    /**
     * XỬ LÝ FORM ĐỔI MẬT KHẨU
     */
    public function reset(Request $request)
    {
        // BƯỚC 1: XÁC THỰC DỮ LIỆU
        $request->validate([
            'token' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        // BƯỚC 2: KIỂM TRA TOKEN TRONG DB
        $reset = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        // BƯỚC 3: KIỂM TRA TOKEN HỢP LỆ + CHƯA HẾT HẠN
        if (!$reset || Carbon::parse($reset->created_at)->addMinutes(60)->isPast()) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Liên kết không hợp lệ hoặc đã hết hạn. Vui lòng yêu cầu lại.']);
        }

        // BƯỚC 4: CẬP NHẬT MẬT KHẨU
        $user = User::where('email', $request->email)->firstOrFail();
        $user->password = Hash::make($request->password);
        $user->save();

        // BƯỚC 5: XÓA TOKEN SAU KHI DÙNG XONG
        DB::table('password_resets')
            ->where('email', $request->email)
            ->delete();

        // BƯỚC 6: CHUYỂN HƯỚNG VỀ TRANG ĐĂNG NHẬP – DÙNG URL CỨNG
        return redirect('login')
            ->with('status', 'Đặt lại mật khẩu thành công! Vui lòng đăng nhập bằng mật khẩu mới.');
    }
}
