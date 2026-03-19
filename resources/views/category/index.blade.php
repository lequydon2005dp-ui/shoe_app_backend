@extends('layout')
@section('content')
    <section class="p-6 overflow-auto flex-1">
        <div class="bg-white rounded-lg shadow-sm p-4">
            <div class="flex justify-between items-center mb-3">
                <h2 class="text-lg font-semibold mb-2">Danh mục sản phẩm</h2>
            </div>
            <div class="flex gap-5">
                <div class="basis-3/12">
                    @include('category.create')
                </div>
                <div class="basis-9/12">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 border border-gray-200 text-left font-medium w-10">#</th>
                                <th class="px-4 border border-gray-300 py-2 text-left font-medium w-20">Hình</th>
                                <th class="px-4 py-2 border border-gray-200 text-left font-medium">Tên danh mục</th>
                                <th class="px-4 py-2 border border-gray-200 text-left font-medium w-28">Hành động</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">

                            @foreach ($categorys as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="border border-gray-100 px-4 py-3">
                                        {{ $item->id }}
                                    </td>
                                    <td class="border border-gray-100 px-4 py-3">
                                        <img src="{{ Storage::url($item->image_url) }}" alt="{{ $item->name }}"
                                            class="w-full">
                                    </td>
                                    <td class="border border-gray-100 px-4 py-3">
                                        {{ $item->name }}
                                    </td>
                                    <td class="border border-gray-100 px-4 py-3">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('category.edit', ['id' => $item->id]) }}"
                                                class="px-2 py-1 rounded border text-xs cursor-pointer">
                                                Sửa
                                            </a>
                                            <form class="inline" method="POST"
                                                action="{{ route('category.destroy', ['id' => $item->id]) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="px-2 py-1 rounded border text-xs cursor-pointer">Xóa</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                    {{ $categorys->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection
