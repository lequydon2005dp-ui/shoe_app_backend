@extends('layout')
@section('content')
    <section class="p-4 overflow-auto flex-1">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex justify-between items-center mb-3">
                <h2 class="text-lg font-semibold mb-2">Cập nhật banner</h2>
                <a href="{{ route('banner.index') }}" class="inline-block px-3 py-1.5 bg-sky-600 text-white rounded-xl">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i>
                    Về danh sách
                </a>
            </div>
            <form action="{{ route('banner.update', ['id' => $banner->id]) }}" enctype="multipart/form-data" method="post">
                @csrf
                @method('POST')
                <div class="mb-4">
                    <label for="name">Tên banner</label>
                    <input name="name" value="{{ old('name', $banner->name) }}" type="text"
                        class="border p-2 w-full rounded-md mb-t">
                    @error('name')
                        <div class="text-red-500">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="">Hình ảnh</label>
                    <input type="file" name="image_url" class="border p-2 w-full rounded-md mb-t">
                    @error('image_url')
                        <div class="text-red-500">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="bg-green-600 text-white px-2 rounded-md py-2">
                    <i class="fa fa-save" aria-hidden="true"></i>
                    Lưu
                </button>
            </form>
        </div>
    </section>
@endsection
