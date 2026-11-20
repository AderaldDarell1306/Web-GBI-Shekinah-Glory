@echo off
title GBI Shekinah Glory - Development Server
echo ========================================================
echo   Memulai Website & Sistem GBI Shekinah Glory
echo ========================================================
echo.

:: Pastikan berada di folder project
cd /d "%~dp0"

:: Cek apakah PHP ada di PATH atau di XAMPP
where php >nul 2>nul
if %errorlevel% equ 0 (
    set PHP_BIN=php
) else (
    set PHP_BIN=C:\xampp\php\php.exe
)

:: Jalankan Laravel Artisan Serve di jendela baru
echo [1/3] Menjalankan Laravel Backend...
start "Laravel Server (GBI Shekinah Glory)" cmd /k "%PHP_BIN% artisan serve"

:: Jalankan Vite Dev Server di jendela baru
echo [2/3] Menjalankan Vite Frontend...
start "Vite Dev Server" cmd /k "npm run dev"

:: Tunggu 3 detik agar server siap, lalu buka browser
echo [3/3] Membuka browser ke http://localhost:8000...
timeout /t 3 /nobreak >nul
start http://localhost:8000

echo.
echo ========================================================
echo   Semua server aktif!
echo   Buka: http://localhost:8000
echo   (Tutup jendela cmd yang terbuka jika ingin mematikan)
echo ========================================================
