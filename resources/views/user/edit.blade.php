@extends('layout')
@section('content')
    <form action="{{ route('user.update', ['id' => $user->id]) }}" enctype="multipart/form-data" method="post">
        @csrf
        <section class="p-6 overflow-auto flex-1">
            <div class="bg-white rounded-lg shadow-sm p-4">
                <div class="flex items-center mb-3">
                    <div class="basis-5/12">
                        <h2 class="text-lg font-semibold mb-2">Thêm Thành viên</h2>
                    </div>
                    <div class="basis-7/12">
                        <div class="w-full flex justify-end">
                            <button type="submit" class="bg-green-600 text-white mr-3 px-3 rounded-md py-2">
                                <i class="fa fa-save" aria-hidden="true"></i>
                                Lưu
                            </button>
                            <a href="{{ route('user.index') }}"
                                class="inline-block px-3 py-1.5 bg-sky-600 text-white rounded-xl">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i>
                                Về danh sách
                            </a>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-5">
                    <div class="mb-4">
                        <label for="name">Họ tên</label>
                        <input name="name" type="text" value="{{ old('name', $user->name) }}"
                            class="border p-2 w-full rounded-md mb-t">
                        @error('name')
                            <div class="text-red-500">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="phone">Điện thoại</label>
                        <input name="phone" type="text" value="{{ old('phone', $user->phone) }}"
                            class="border p-2 w-full rounded-md mb-t">
                        @error('phone')
                            <div class="text-red-500">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="email">Email</label>
                        <input name="email" type="text" value="{{ old('email', $user->email) }}"
                            class="border p-2 w-full rounded-md mb-t">
                        @error('email')
                            <div class="text-red-500">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="password">Mật khẩu</label>
                        <input name="password" type="password" value="{{ old('password') }}"
                            class="border p-2 w-full rounded-md mb-t">
                        @error('password')
                            <div class="text-red-500">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="address">Địa chỉ</label>
                        <input name="address" type="text" value="{{ old('address', $user->address) }}"
                            class="border p-2 w-full rounded-md mb-t">
                        @error('address')
                            <div class="text-red-500">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="avatar">Hình đại diện</label>
                        <input type="file" name="avatar" class="border p-2 w-full rounded-md mb-t">
                        @error('avatar')
                            <div class="text-red-500">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </section>
    </form>
@endsection
