<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */ // <-- THÊM DÒNG NÀY
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $notifications = $user->notifications()
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'data' => $this->safeJson($notification->data),
                    'read' => (bool) $notification->read,
                    'read_at' => $notification->read_at,
                    'created_at' => $notification->created_at->format('Y-m-d H:i:s'),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $notifications,
        ]);
    }
    private function safeJson($data)
    {
        // ✅ Nếu đã là mảng rồi thì trả luôn
        if (is_array($data)) {
            return $data;
        }

        // ✅ Nếu null hoặc rỗng thì trả mảng rỗng
        if (is_null($data) || $data === '') {
            return [];
        }

        // ✅ Nếu là chuỗi JSON thì decode
        if (is_string($data)) {
            $decoded = json_decode($data, true);
            return $decoded ?? [];
        }

        // ✅ Phòng trường hợp khác (object, int...)
        return [];
    }
    public function markAsRead($id)
    {
        $notification = Notification::where('user_id', Auth::id())->find($id);

        if (!$notification) {
            return response()->json(['message' => 'Notification not found'], 404);
        }

        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read',
        ]);
    }
}
