<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Arr;

use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:user',
                'phone' => 'required|string|max:20',
                'password' => 'required|string|min:6|confirmed',
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
            ]);

            return response()->json([
                'status' => true,
                'message' => 'User registered successfully',
                'user' => $user->only(['id', 'name', 'email', 'phone', 'address', 'avatar', 'created_at', 'updated_at']),
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid login details',
            ], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Login successful',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user->only(['id', 'name', 'email', 'phone', 'address', 'avatar', 'created_at', 'updated_at']),
        ]);
    }

    /**
     * GET /api/user
     * LẤY THÔNG TIN USER ĐÃ ĐĂNG NHẬP
     */
    public function user(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'status' => true,
            'user' => $user->only(['id', 'name', 'email', 'phone', 'address', 'avatar', 'created_at', 'updated_at']),
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Successfully logged out',
        ]);
    }

    /**
     * PUT /api/user/update
     */
    public function update(Request $request)
    {
        $user = $request->user();

        try {
            $validatedData = $request->validate([
                'name' => 'nullable|string|max:255',
                'email' => 'nullable|string|email|max:255|unique:user,email,' . $user->id,
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:500',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        }

        $dataToUpdate = Arr::where($validatedData, function ($value) {
            return $value !== null;
        });

        foreach ($dataToUpdate as $key => $value) {
            if ($value === '') {
                $dataToUpdate[$key] = null;
            }
        }

        if (!empty($dataToUpdate)) {
            $user->update($dataToUpdate);
        }
        Notification::create([
            'user_id' => Auth::id(),
            'title' => 'Cập nhật thông tin thành công!',
            'message' => "Tài khoản của bạn đã cập nhật thông tin thành công.",
            'data' => json_encode([
                'created_at' => $user->created_at->format('d/m/Y H:i'),
            ]),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'User profile updated successfully',
            'user' => $user->refresh()->only(['id', 'name', 'email', 'phone', 'address', 'avatar', 'created_at', 'updated_at']),
        ]);
    }

    /**
     * POST /api/user/update-avatar
     */
    public function updateAvatar(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Xóa ảnh cũ nếu có
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Lưu ảnh mới
        $path = $request->file('avatar')->store('avatars', 'public');
        $url = asset('storage/' . $path); // ✅ tạo URL đầy đủ cho client

        $user->update(['avatar' => $path]);

        return response()->json([
            'status' => true,
            'avatar' => $url, // ✅ trả về link đầy đủ
        ]);
    }
    public function changePassword(Request $request)
    {
        // 1. Validate request
        $validatedData = $request->validate([
            'current_password' => 'required|string',
            'new_password' => [
                'required',
                'string',
                'confirmed', // Phải có trường 'new_password_confirmation' giống hệt
                Password::min(8) // Yêu cầu mật khẩu mới tối thiểu 8 ký tự
                    ->mixedCase() // (Tùy chọn) Phải có chữ hoa và chữ thường
                    ->numbers()   // (Tùy chọn) Phải có số
                    ->symbols(),  // (Tùy chọn) Phải có ký tự đặc biệt
            ],
        ]);

        // 2. Lấy thông tin user hiện tại
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 3. Kiểm tra mật khẩu hiện tại có đúng không
        if (!Hash::check($validatedData['current_password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Mật khẩu hiện tại không chính xác.',
            ], 422); // 422 Unprocessable Entity
        }

        // 4. Cập nhật mật khẩu mới
        try {
            $user->password = Hash::make($validatedData['new_password']);

            $user->save();
            Notification::create([
                'user_id' => Auth::id(),
                'title' => 'Đổi mật khẩu thành công!',
                'message' => "Tài khoản của bạn đã đổi mật khẩu thành công.",
                'data' => json_encode([
                    'created_at' => $user->created_at->format('d/m/Y H:i'),
                ]),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Đổi mật khẩu thành công!',
            ]);
        } catch (\Exception $e) {
            // Ghi lại log lỗi nếu cần
            // Log::error('Lỗi đổi mật khẩu: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi, vui lòng thử lại.',
            ], 500); // 500 Internal Server Error
        }
    }
}
