<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Trang Quản Lý - Dashboard</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.css" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-papM0f2+6QY0Q2YJr+vQK1q2y4m2+e2z9k5q1KQ6g5K8sH3Dq1eZs0Yc6f3k1p7l8s9Y6j8m4n3b2c1a0Z"
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        /* Small visual polish beyond Tailwind utilities */
        .sidebar-item:hover {
            background: linear-gradient(90deg, rgba(59, 130, 246, 0.08), transparent);
        }
    </style>
    @yield('header')
</head>

<body class="bg-gray-50 text-gray-800">
    <div class="flex h-screen">
        <!-- Sidebar (2/12) -->
        <aside class="basis-2/12 bg-white border-r shadow-sm p-4 flex flex-col">
            <!-- Logo -->
            <div class="flex items-center gap-3 mb-6 px-2">
                <div
                    class="w-10 h-10 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white text-lg font-bold">
                    SM</div>
                <div>
                    <h1 class="text-lg font-semibold">HỒ DIÊN LỢI</h1>
                    <p class="text-xs text-gray-500">Admin Panel</p>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1">
                <ul class="space-y-1">
                    <li
                        class="sidebar-item rounded-md p-2 flex items-center gap-3 text-sm font-medium text-gray-700 hover:text-indigo-600">
                        <a href="{{ route('dashboard') }}">
                            <i class="fas fa-tachometer-alt w-5 text-lg"></i>
                            <span>Bảng điều khiển</span>
                        </a>
                    </li>
                    <li
                        class="sidebar-item rounded-md p-2 flex items-center gap-3 text-sm font-medium text-gray-700 hover:text-indigo-600">
                        <a href="{{ route('product.index') }}">
                            <i class="fas fa-box w-5 text-lg"></i>
                            <span>Sản phẩm</span>
                        </a>
                    </li>
                    <li
                        class="sidebar-item rounded-md p-2 flex items-center gap-3 text-sm font-medium text-gray-700 hover:text-indigo-600">
                        <a href="{{ route('category.index') }}">
                            <i class="fas fa-list-ul w-5 text-lg"></i>
                            <span>Danh mục</span>
                        </a>
                    </li>
                    <li
                        class="sidebar-item rounded-md p-2 flex items-center gap-3 text-sm font-medium text-gray-700 hover:text-indigo-600">
                        <a href="{{ route('order.index') }}">
                            <i class="fas fa-receipt w-5 text-lg"></i>
                            <span>Đơn hàng</span>
                        </a>
                    </li>
                    <li
                        class="sidebar-item rounded-md p-2 flex items-center gap-3 text-sm font-medium text-gray-700 hover:text-indigo-600">
                        <a href="{{ route('banner.index') }}">
                            <i class="fas fa-image w-5 text-lg"></i>
                            <span>Banner</span>
                        </a>
                    </li>
                    <li
                        class="sidebar-item rounded-md p-2 flex items-center gap-3 text-sm font-medium text-gray-700 hover:text-indigo-600">
                        <a href="{{ route('user.index') }}">
                            <i class="fas fa-user-friends w-5 text-lg"></i>
                            <span>Thành viên</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- Optional: small footer in sidebar -->
            <div class="mt-4 text-xs text-gray-500 px-2">
                <p>© 2025 ShopManager</p>
            </div>
        </aside>

        <!-- Main content (10/12) -->
        <main class="basis-10/12 flex flex-col">
            <!-- Header -->
            <header class="flex items-center justify-between bg-white border-b p-4">
                <div class="flex items-center gap-4">

                </div>

                <div class="flex items-center gap-3">
                    <button
                        class="flex items-center gap-2 px-3 py-2 rounded-md text-sm text-red-600 hover:bg-red-50 border border-red-100">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Đăng xuất</span>
                    </button>
                </div>
            </header>

            <!-- Content area -->
            @yield('content')

            <!-- Footer -->
            <footer class="bg-white border-t p-4 text-sm text-gray-600">
                <div class="text-center">Họ Tên Sinh Viên</div>
            </footer>
        </main>
    </div>

    <!-- Small script to demonstrate simple interactivity -->
    <script>
        // Example: highlight active menu (for demo purposes only)
        document.querySelectorAll('.sidebar-item').forEach((el, idx) => {
            el.addEventListener('click', () => {
                document.querySelectorAll('.sidebar-item').forEach(x => x.classList.remove('bg-indigo-50',
                    'text-indigo-600'));
                el.classList.add('bg-indigo-50', 'text-indigo-600');
            });
        });
    </script>
    @yield('footer')
    @include('notifications')
</body>

</html>
