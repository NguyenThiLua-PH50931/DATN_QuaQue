# Auto Delete Trashed Items PowerShell Script
# Chạy lệnh tự động xóa các item trong thùng rác

Write-Host "========================================" -ForegroundColor Green
Write-Host "    AUTO DELETE TRASHED ITEMS" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host ""

$currentTime = Get-Date -Format "yyyy-MM-dd HH:mm:ss"
Write-Host "Starting automatic cleanup at: $currentTime" -ForegroundColor Yellow
Write-Host ""

# Chuyển đến thư mục project
Set-Location "C:\laragon\www\DATN\DATN_QuaQue"
Write-Host "Current directory: $(Get-Location)" -ForegroundColor Cyan
Write-Host ""

# Chạy lệnh cleanup
Write-Host "Running cleanup command..." -ForegroundColor Yellow
try {
    & php artisan trashed:cleanup-all
    Write-Host "Cleanup completed successfully!" -ForegroundColor Green
} catch {
    Write-Host "Error during cleanup: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host ""
Write-Host "========================================" -ForegroundColor Green
Write-Host "    CLEANUP COMPLETED" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host ""

$endTime = Get-Date -Format "yyyy-MM-dd HH:mm:ss"
Write-Host "Completed at: $endTime" -ForegroundColor Yellow
Write-Host ""

Read-Host "Press Enter to continue..."
