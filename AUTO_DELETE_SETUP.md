# Hướng dẫn thiết lập tự động xóa thùng rác

## 📋 Tổng quan
Hệ thống tự động xóa các item đã xóa mềm sau 30 ngày. Để hoạt động, cần thiết lập cron job hoặc Task Scheduler.

## 🚀 Cách 1: Sử dụng Task Scheduler (Windows) - Khuyến nghị

### Bước 1: Test file batch
1. Chạy file `auto_delete_trashed.bat` để test
2. Đảm bảo lệnh hoạt động bình thường

### Bước 2: Thiết lập Task Scheduler
1. Mở **Task Scheduler** (Win + R → `taskschd.msc`)
2. Click **Create Basic Task**
3. Đặt tên: `Auto Delete Trashed Items`
4. Chọn **Daily**
5. Đặt thời gian: **2:00 AM** (khuyến nghị)
6. Chọn **Start a program**
7. Browse đến file: `C:\laragon\www\DATN\DATN_QuaQue\auto_delete_trashed.bat`
8. Hoàn thành

### Bước 3: Cấu hình nâng cao
1. Click chuột phải vào task vừa tạo
2. Chọn **Properties**
3. Tab **General**: Chọn **Run whether user is logged on or not**
4. Tab **Conditions**: Bỏ chọn **Start the task only if the computer is on AC power**
5. Tab **Settings**: Chọn **Allow task to be run on demand**
6. Click **OK**

## 🔧 Cách 2: Sử dụng PowerShell Script

### Bước 1: Test script
```powershell
Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser
.\auto_delete_trashed.ps1
```

### Bước 2: Thiết lập Task Scheduler với PowerShell
1. Tương tự như batch, nhưng chọn file `.ps1`
2. Program: `powershell.exe`
3. Arguments: `-ExecutionPolicy Bypass -File "C:\laragon\www\DATN\DATN_QuaQue\auto_delete_trashed.ps1"`

## 📊 Các lệnh có thể sử dụng

### Xóa tất cả các loại item
```bash
php artisan trashed:cleanup-all
```

### Xóa chỉ một loại cụ thể
```bash
php artisan trashed:cleanup-all --type=banners
php artisan trashed:cleanup-all --type=products
php artisan trashed:cleanup-all --type=categories
php artisan trashed:cleanup-all --type=regions
php artisan trashed:cleanup-all --type=attributes
php artisan trashed:cleanup-all --type=comments
php artisan trashed:cleanup-all --type=blogs
php artisan trashed:cleanup-all --type=coupons
```

### Thay đổi số ngày (mặc định 30)
```bash
php artisan trashed:cleanup-all --days=15
php artisan trashed:cleanup-all --type=banners --days=7
```

## ✅ Kiểm tra hoạt động

### 1. Kiểm tra log
- Xem log trong `storage/logs/laravel.log`
- Hoặc log của Task Scheduler

### 2. Test thủ công
- Chạy lệnh trực tiếp để kiểm tra
- Vào các trang thùng rác để xem nút "Kiểm tra tự động xóa"

### 3. Kiểm tra dữ liệu
- Vào database xem các item có `deleted_at` cũ
- Kiểm tra xem có bị xóa vĩnh viễn không

## 🚨 Lưu ý quan trọng

- **Backup dữ liệu** trước khi thiết lập
- **Test kỹ** trước khi chạy tự động
- **Kiểm tra quyền** của Task Scheduler
- **Theo dõi log** để đảm bảo hoạt động đúng
- **Có thể test** với `--days=1` để xem kết quả nhanh

## 🔍 Troubleshooting

### Lỗi thường gặp:
1. **PHP path không đúng**: Sửa đường dẫn PHP trong file batch
2. **Quyền không đủ**: Chạy Task Scheduler với quyền Administrator
3. **Thư mục không đúng**: Kiểm tra đường dẫn trong file batch
4. **Laravel chưa sẵn sàng**: Đảm bảo Laravel đã được cài đặt đầy đủ

### Kiểm tra:
1. Chạy file batch thủ công
2. Kiểm tra log Laravel
3. Kiểm tra quyền Task Scheduler
4. Kiểm tra đường dẫn PHP và Laravel
