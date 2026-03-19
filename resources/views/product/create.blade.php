@extends('layout')
@section('content')
    <form action="{{ route('product.store') }}" enctype="multipart/form-data" method="post">
        @csrf
        <section class="p-6 overflow-auto flex-1">
            <div class="bg-white rounded-lg shadow-sm p-4">
                <div class="flex items-center mb-3">
                    <div class="basis-5/12">
                        <h2 class="text-lg font-semibold mb-2">Thêm sản phẩm</h2>
                    </div>
                    <div class="basis-7/12">
                        <div class="w-full flex justify-end">
                            <button type="submit" class="bg-green-600 text-white mr-3 px-3 rounded-md py-2">
                                <i class="fa fa-save" aria-hidden="true"></i>
                                Lưu
                            </button>
                            <a href="{{ route('product.index') }}"
                                class="inline-block px-3 py-1.5 bg-sky-600 text-white rounded-xl">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i>
                                Về danh sách
                            </a>
                        </div>
                    </div>
                </div>
                <div class="flex gap-5">
                    <div class="basis-9/12">
                        <div class="mb-4">
                            <label for="name">Tên sản phẩm</label>
                            <input name="name" type="text" value="{{ old('name') }}"
                                class="border p-2 w-full rounded-md mb-t">
                            @error('name')
                                <div class="text-red-500">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="content">Nội dung</label>
                            <textarea name="content" rows="7" type="text" class="border p-2 w-full rounded-md mb-t">{{ old('content') }}</textarea>
                            @error('content')
                                <div class="text-red-500">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="basis-3/12">
                        <div class="mb-4">
                            @php
                                $selected = old('category_id');
                            @endphp
                            <label for="category_id">Danh mục</label>
                            <select name="category_id" id="category_id" class="border p-2 w-full rounded-md mb-t">
                                <option value="">Chọn danh mục</option>
                                @foreach ($categorys as $cat)
                                    <option value="{{ $cat->id }}" {{ $selected == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="text-red-500">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="price">Giá</label>
                            <input name="price" type="number" value="{{ old('price') }}"
                                class="border p-2 w-full rounded-md mb-t">
                            @error('price')
                                <div class="text-red-500">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="price_discount">Giá khuyến mãi</label>
                            <input name="price_discount" value="{{ old('price_discount') }}" type="number"
                                class="border p-2 w-full rounded-md mb-t">
                            @error('price_discount')
                                <div class="text-red-500">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <div class="flex justify-center">
                                <img src="" alt="" class="w-1/2" id="showimage">
                            </div>
                            <div class="mb-4">
                                <label for="">Hình đại diện</label>
                                <input type="file" name="image_url" id="image_url"
                                    class="border p-2 w-full rounded-md mb-t" onchange="handSelectImage()">
                            </div>
                            @error('image_url')
                                <div class="text-red-500">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </form>
@endsection
@section('footer')
    <script>
        function handSelectImage() {
            const fileInput = document.querySelector("#image_url");
            const file = fileInput.files[0];
            if (!file) return;
            const imageUrl = URL.createObjectURL(file);
            document.querySelector("#showimage").src = imageUrl;
        }
    </script>
@endsection
