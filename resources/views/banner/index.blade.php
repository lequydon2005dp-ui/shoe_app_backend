@extends('layout')
@section('content')
    <section class="p-4 overflow-auto flex-1">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex justify-between items-center mb-3">
                <h2 class="text-lg font-semibold mb-2">Danh sách banner</h2>
            </div>
            <div class="flex gap-5">
                <div class="basis-3/12">
                    @include('banner.create')
                </div>
                <div class="basis-9/12">
                    <table class="w-full divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 border border-gray-300 py-2 text-center font-medium w-10">#</th>
                                <th class="px-4 border border-gray-300 py-2 text-left font-medium w-20">Hình</th>
                                <th class="px-4 border border-gray-300 py-2 text-left font-medium">Tên banner</th>
                                <th class="px-4 border border-gray-300 py-2 font-medium w-30 text-center">Hành động</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($banners as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="border border-gray-200 px-4 py-3 text-center">
                                        {{ $item->id }}
                                    </td>
                                    <td class="border border-gray-200">
                                        <img src="{{ Storage::url($item->image_url) }}" alt="{{ $item->name }}"
                                            class="w-full">
                                    </td>
                                    <td class="border border-gray-200 px-4 py-3">
                                        {{ $item->name }}
                                    </td>
                                    <td class="border border-gray-200 px-4 py-3 text-center">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('banner.edit', ['id' => $item->id]) }}"
                                                class="px-2 py-1 rounded border text-xs cursor-pointer">
                                                Sửa
                                            </a>
                                            <form class="inline" method="POST"
                                                action="{{ route('banner.destroy', ['id' => $item->id]) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="px-2 py-1 bg-red-500 rounded border text-xs text-white cursor-pointer">Xóa</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $banners->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection
