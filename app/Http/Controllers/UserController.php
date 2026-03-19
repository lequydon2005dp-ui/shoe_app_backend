<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    public function index()
    {
        $users = User::query()->orderBy('created_at', 'desc')->paginate(20);
        return view('user.index', compact('users'));
    }
    public function create()
    {
        return view('user.create');
    }
    public function store(StoreUserRequest $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->address = $request->address;
        //upload file
        $file = $request->file('avatar');
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        // lưu vào disk public (storage/app/public)
        $path = $file->storeAs('images/users', $filename, 'public'); // returns images/xxxxx.jpg
        $user->image_url = $path;
        //end upload file
        if ($user->save()) {
            return redirect()->route('user.index')->with('success', 'Thêm thành công');;
        }
    }
    public function edit($id)
    {
        $user = User::find($id);
        if ($user == null) {
            return redirect()->route('user.index')->with('error', 'Không tìm thấy thông tin!');
        }
        return view('user.edit', compact('user'));
    }
    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::find($id);
        if ($user == null) {
            return redirect()->route('user.index')->with('error', 'Không tìm thấy thông tin!');
        }
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->address = $request->address;
        //upload file
        if ($request->hasFile('avatar')) {
            // xóa avatar cũ nếu có
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $file = $request->file('avatar');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('images/users', $filename, 'public'); // returns images/xxxxx.jpg
            $user->avatar = $path;
        }
        //end upload file
        if ($user->save()) {
            return redirect()->route('user.index')->with('success', 'Cập nhật thành công');;
        }
    }
    public function destroy($id)
    {
        $user = User::find($id);
        if ($user == null) {
            return redirect()->route('user.index')->with('error', 'Không tìm thấy thông tin!');
        }
        $user_image = $user->avatar;
        if ($user->delete()) {
            if ($user_image && Storage::disk('public')->exists($user_image)) {
                Storage::disk('public')->delete($user_image);
            }
            return redirect()->route('user.index')->with('success', 'Xóa thành công!');
        }
    }
}
