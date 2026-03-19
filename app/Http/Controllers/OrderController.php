<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderStatusUpdated;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user') // ← THÊM DÒNG NÀY
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('order.index', compact('orders'));
    }
    public function contact()
    {
        return view('contact.contact');
    }
    public function store(Request $request)
    {
        $request->validate([
            'image_url' => 'required|image|max:5120', // max 5MB
        ]);
        $order = new Order();
        $order->name = $request->name;
        //upload file
        $file = $request->file('image_url');
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        // lưu vào disk public (storage/app/public)
        $path = $file->storeAs('images/orders', $filename, 'public'); // returns images/xxxxx.jpg
        $order->image_url = $path;
        //end upload file
        if ($order->save()) {
            return redirect()->route('order.index')->with('success', 'Thêm thành công');;
        }
    }
    public function edit($id)
    {
        $order = Order::find($id);
        return view('order.edit', compact('order'));
    }
    public function show($id)
    {
        $order = Order::find($id);
        return view('order.show', compact('order'));
    }
    public function showuser($id)
    {
        $order = Order::find($id);
        return view('order.showuser', compact('order'));
    }
    public function update(Request $request, $id)
    {
        // $request->validate([
        //     'image_url' => '|image|max:5120', // max 5MB
        // ]);
        $order = Order::find($id);
        $order->name = $request->name;
        //upload file
        if ($request->has('image_url')) {
            $file = $request->file('image_url');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('images/orders', $filename, 'public'); // returns images/xxxxx.jpg
            $order->image_url = $path;
        }
        //end upload file
        if ($order->save()) {
            return redirect()->route('order.index');
        }
    }
    public function destroy($id)
    {
        $order = Order::find($id);
        if ($order->delete()) {
            return redirect()->route('order.index');
        }
    }
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $oldStatus = $order->status;
        $newStatus = $request->status;

        // Cập nhật trạng thái
        $order->status = $newStatus;
        $order->save();
        $user = $order->user;

        if ($user) {
            Notification::create([
                'user_id' => $user->id,
                'title'   => 'Trạng thái đơn hàng đã được thay đổi!',
                'message' => "Đơn hàng #{$order->id} đã thay đổi trạng thái {$newStatus}.",
                'data'    => json_encode([
                    'order_id'   => $order->id,
                    'total'      => $order->total_amount,
                    'status'     => $order->status,
                    'created_at' => now()->format('d/m/Y H:i'),
                ]),
            ]);
        }
        // Gửi email thông báo nếu trạng thái thay đổi
        if ($oldStatus !== $newStatus && $order->user && $order->user->email) {
            Mail::to($order->user->email)->send(new OrderStatusUpdated($order));
        }

        return redirect()->route('order.show', $order->id)
            ->with('success', 'Cập nhật trạng thái đơn hàng và gửi email thành công!');
    }
}
