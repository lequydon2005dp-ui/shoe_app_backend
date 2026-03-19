@extends('layout')
@section('content')
    <section class="p-6 overflow-auto flex-1">
        <div class="bg-white rounded-lg shadow-sm p-4">
            <div class="flex justify-between items-center mb-3">
                <h2 class="text-lg font-semibold mb-2">Danh sách sản phẩm</h2>
                <a href="{{ route('product.create') }}" class="inline-block px-3 py-1.5 bg-green-600 text-white rounded-xl">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                    Thêm
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 border border-gray-200 text-center font-medium w-10">#</th>
                            <th class="px-4 py-2 border border-gray-200 text-left font-medium w-28">Hình</th>
                            <th class="px-4 py-2 border border-gray-200 text-left font-medium">Tên sản phẩm</th>
                            <th class="px-4 py-2 border border-gray-200 text-left font-medium">Danh mục</th>
                            <th class="px-4 py-2 border border-gray-200 text-left font-medium">Giá</th>
                            <th class="px-4 py-2 border border-gray-200 text-left font-medium">Khuyến mãi</th>
                            <th class="px-4 py-2 border border-gray-200 text-center font-medium w-28">Hành động</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($products as $item)
                            @php
                                $cat = $item->category;
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 border border-gray-100">
                                    {{ $item->id }}
                                </td>
                                <td class="p-1 border border-gray-100">
                                    <img src="{{ Storage::url($item->image_url) }}" alt="{{ $item->name }}"
                                        class="w-full">
                                </td>
                                <td class="px-4 py-3 border border-gray-100">
                                    {{ $item->name }}
                                </td>
                                <td class="px-4 py-3 border border-gray-100">
                                    {{ $cat->name }}
                                </td>
                                <td class="px-4 py-3 border border-gray-100">
                                    {{ $item->price }}
                                </td>
                                <td class="px-4 py-3 border border-gray-100">
                                    {{ $item->price_discount }}
                                </td>
                                <td class="px-4 py-3 border border-gray-100">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('product.edit', ['id' => $item->id]) }}"
                                            class="px-2 py-1 rounded border text-xs text-white bg-sky-400 cursor-pointer">
                                            Sửa
                                        </a>
                                        <form class="inline" method="POST"
                                            action="{{ route('product.destroy', ['id' => $item->id]) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="px-2 py-1 rounded border text-xs cursor-pointer text-white bg-red-400">Xóa</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
                {{ $products->links() }}
            </div>
        </div>
    </section>
@endsection
