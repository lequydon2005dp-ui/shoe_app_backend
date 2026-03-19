# ⚙️ Shoe Store Backend API

Hệ thống RESTful API phục vụ cho ứng dụng di động Shoe Store, xử lý logic nghiệp vụ, quản lý cơ sở dữ liệu và hệ thống gửi mail tự động.

## ✨ Tính năng nổi bật
* **Cung cấp RESTful API:** Chuẩn hóa các API Endpoints cho Frontend (Authentication, Products, Cart, Orders).
* **Xác thực bảo mật:** Quản lý đăng nhập, đăng ký và phân quyền người dùng.
* **Hệ thống Email tự động:** Tích hợp SMTP gửi email thông báo khi người dùng thay đổi mật khẩu hoặc cập nhật tài khoản.

## 🛠 Công nghệ sử dụng
* **Framework:** Laravel (PHP)
* **Cơ sở dữ liệu:** MySQL
* **Công cụ test API:** Postman

## 🚀 Hướng dẫn cài đặt

**1. Yêu cầu hệ thống**
* PHP >= 8.1
* Composer
* XAMPP (hoặc MySQL server tương đương)

**2. Cài đặt và khởi chạy**
Clone dự án về máy:
\`\`\`
git clone https://github.com/ten-cua-ban/ten-repo-backend.git
cd ten-repo-backend
\`\`\`

Cài đặt các gói thư viện (Dependencies):
\`\`\`
composer install
\`\`\`

Cấu hình môi trường:
Copy file `.env.example` thành `.env` và thiết lập kết nối Database & SMTP Mail:
\`\`\`env

DB_CONNECTION=mysql

DB_HOST=127.0.0.1

DB_PORT=3306

DB_DATABASE=laravelapp

DB_USERNAME=root

DB_PASSWORD=

MAIL_MAILER=smtp

MAIL_HOST=smtp.gmail.com

MAIL_PORT=587

MAIL_USERNAME=your_email@gmail.com

MAIL_PASSWORD=your_app_password

MAIL_ENCRYPTION=tls

\`\`\`

Mở XAMPP chọn Import chọn thư mục db trong Dự Án Shoe_App_Backend chọn file laravelapp để có dữ liệu. 

Tạo Application Key và chạy Migration (tạo bảng trong CSDL):
\`\`\`
php artisan key:generate
php artisan migrate
\`\`\`

Khởi chạy Server cục bộ:
\`\`\`
php artisan serve
# Server sẽ chạy tại: http://127.0.0.1:8000
\`\`\`
