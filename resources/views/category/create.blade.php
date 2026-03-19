<form action="{{ route('category.store') }}" enctype="multipart/form-data" method="post">
    @csrf
    <div class="mb-4">
        <label for="name">Tên danh mục</label>
        <input name="name" type="text" class="border p-2 w-full rounded-md mb-t">
        @error('name')
            <div class="text-red-500">{{ $message }}</div>
        @enderror
    </div>
    <div class="mb-4">
        <label for="">Hình đại diện</label>
        <input type="file" name="image_url" class="border p-2 w-full rounded-md mb-t">
        @error('image_url')
            <div class="text-red-500">{{ $message }}</div>
        @enderror
    </div>
    <button type="submit" class="bg-green-600 text-white w-full rounded-md py-2">
        <i class="fa fa-save" aria-hidden="true"></i>
        Lưu
    </button>
</form>
