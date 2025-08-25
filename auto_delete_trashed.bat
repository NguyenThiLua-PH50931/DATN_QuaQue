@echo off
echo ========================================
echo    AUTO DELETE TRASHED ITEMS
echo ========================================
echo.
echo Starting automatic cleanup of trashed items...
echo Time: %date% %time%
echo.

cd /d "C:\laragon\www\DATN\DATN_QuaQue"

echo Current directory: %cd%
echo.

echo Running cleanup command...
echo PHP Path: C:\laragon\bin\php\php-8.2.20-Win32-vs16-x64\php.exe

REM Kiểm tra PHP có tồn tại không
if exist "C:\laragon\bin\php\php-8.2.20-Win32-vs16-x64\php.exe" (
    echo PHP found, running cleanup...
    C:\laragon\bin\php\php-8.2.20-Win32-vs16-x64\php.exe artisan trashed:cleanup-all
    if %errorlevel% neq 0 (
        echo ERROR: Cleanup failed with code %errorlevel%
        echo Please check Laravel installation
    ) else (
        echo Cleanup completed successfully!
    )
) else (
    echo ERROR: PHP not found at C:\laragon\bin\php\php-8.2.20-Win32-vs16-x64\php.exe
    echo Please check PHP path
)

echo.
echo ========================================
echo    CLEANUP COMPLETED
echo ========================================
echo.
echo Time: %date% %time%
echo.
pause
