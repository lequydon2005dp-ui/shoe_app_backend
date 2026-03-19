<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Mail\PasswordResetMail;
use Carbon\Carbon;

class PasswordResetController extends Controller
{
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Email không tồn tại.',
            ], 404);
        }

        // XÓA TOKEN CŨ
        DB::table('password_resets')->where('email', $user->email)->delete();

        // TẠO TOKEN MỚI
        $token = Str::random(60);
        DB::table('password_resets')->insert([
            'email' => $user->email,
            'token' => $token,
            'created_at' => Carbon::now(),
        ]);

        // GỬI EMAIL VỚI LINK TRỎ ĐẾN TRANG WEB
        $resetUrl = route('emails.orders.password-reset', [
            'token' => $token,
            'email' => $user->email
        ]);

        Mail::to($user->email)->send(new PasswordResetMail($resetUrl));

        return response()->json([
            'status' => true,
            'message' => 'Link đặt lại mật khẩu đã được gửi!',
        ]);
    }
}