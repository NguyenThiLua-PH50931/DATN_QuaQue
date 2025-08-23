🚀 HƯỚNG DẪN THIẾT LẬP TASK SCHEDULER
Bước 1: Mở Task Scheduler
Nhấn Win + R (hoặc Windows + R)
Gõ taskschd.msc
Nhấn Enter

Bước 2: Tạo Task mới
Trong Task Scheduler, click Create Basic Task (bên phải)
Đặt tên: Auto Delete Trashed Items
Mô tả: Tự động xóa các item đã xóa mềm sau 30 ngày
Click Next

Bước 3: Thiết lập lịch chạy
Chọn Daily
Click Next
Đặt thời gian bắt đầu: 2:00 AM (khuyến nghị)
Click Next

Bước 4: Thiết lập hành động
Chọn Start a program
Click Next
Program/script: Browse đến file C:\laragon\www\DATN\DATN_QuaQue\auto_delete_trashed.bat
Click Next

Bước 5: Hoàn thành cơ bản
Xem lại thông tin
Click Finish

⚙️ CẤU HÌNH NÂNG CAO (QUAN TRỌNG)
Bước 6: Cấu hình nâng cao
Trong Task Scheduler, tìm task vừa tạo
Click chuột phải vào task → chọn Properties

Bước 7: Tab General
Chọn Run whether user is logged on or not
Chọn Run with highest privileges
Click OK nếu có thông báo về quyền

Bước 8: Tab Conditions
Bỏ chọn Start the task only if the computer is on AC power
Chọn Wake the computer to run this task (nếu muốn)

Bước 9: Tab Settings
Chọn Allow task to be run on demand
Chọn If the task fails, restart every: 1 minute, restart up to: 3 times
🧪 TEST TASK SCHEDULER

Bước 10: Test ngay
Click chuột phải vào task → Run
Kiểm tra xem có chạy thành công không
Xem log trong History tab

Bước 11: Kiểm tra log
Mở History tab trong Properties
Xem có lỗi gì không
Kiểm tra log Laravel: storage/logs/laravel.log
�� KIỂM TRA HOẠT ĐỘNG

Bước 12: Vào các trang thùng rác
Vào Banners → Thùng rác
Click Kiểm tra tự động xóa
Xem thông tin hiển thị

Bước 13: Test lệnh thủ công
�� LƯU Ý QUAN TRỌNG
Backup dữ liệu trước khi thiết lập
Test kỹ trước khi chạy tự động
Kiểm tra quyền Administrator
Theo dõi log để đảm bảo hoạt động đúng
✅ KẾT QUẢ SAU KHI THIẾT LẬP
Sau khi hoàn thành, hệ thống sẽ:
Tự động chạy hàng ngày vào 2:00 AM
Xóa vĩnh viễn các item đã xóa mềm sau 30 ngày
Không cần can thiệp thủ công
Log đầy đủ quá trình thực hiện
