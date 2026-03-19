@extends('layout')
@section('content')
<section class="p-6 overflow-auto flex-1">
    <div class="bg-white rounded-lg shadow-sm p-4">
        <h2 class="text-lg font-semibold mb-4">Danh sách đơn hàng</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 border border-gray-200 text-center font-medium w-10">#</th>
                        <th class="px-4 py-2 border border-gray-200 text-left font-medium">Họ tên</th>
                        <th class="px-4 py-2 border border-gray-200 text-left font-medium">Email</th>
                        <th class="px-4 py-2 border border-gray-200 text-left font-medium">Điện thoại</th>
                        <th class="px-4 py-2 border border-gray-200 text-left font-medium">Địa chỉ</th>
                        <th class="px-4 py-2 border border-gray-200 text-center font-medium w-40">Hành động</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">

                    @foreach ($orders as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 border border-gray-100">
                            {{ $item->id }}
                        </td>
                        <td class="px-4 py-3 border border-gray-100">
                            {{ $item->user->name }}
                        </td>
                        <td class="px-4 py-3 border border-gray-100">
                            {{ $item->email }}
                        </td>
                        <td class="px-4 py-3 border border-gray-100">
                            {{ $item->phone }}
                        </td>
                        <td class="px-4 py-3 border border-gray-100">
                            {{ $item->address }}
                        </td>
                        <td class="px-4 py-3 border border-gray-100 text-center">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('order.show', ['id' => $item->id]) }}"
                                    class="px-2 py-1 rounded border text-xs cursor-pointer">
                                    Chi tiết
                                </a>
                                <form class="inline" method="POST"
                                    action="{{ route('order.destroy', ['id' => $item->id]) }}">
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
            {{ $orders->links() }}
        </div>
    </div>
</section>
@endsection