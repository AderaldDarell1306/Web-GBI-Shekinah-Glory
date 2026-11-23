@echo off
title GBI Shekinah Glory - Cloudflare Tunnel (Online)
echo ========================================================
echo   Mengonlinekan Website GBI Shekinah Glory ke Internet
echo   (Powered by Cloudflare Tunnel - Bebas Kartu Kredit)
echo ========================================================
echo.

cd /d "%~dp0"

:: 1. Cek / Set PHP Binary
where php >nul 2>nul
if %errorlevel% equ 0 (
    set PHP_BIN=php
) else (
    set PHP_BIN=C:\xampp\php\php.exe
)

:: 2. Pastikan file asset Vite sudah ter-compile (agar tampilan CSS/JS tidak rusak di internet)
if not exist "public\build\manifest.json" (
    echo [*] Mengompilasi asset tampilan (Vite build)...
    call npm run build
    echo.
)

:: 3. Cek apakah cloudflared.exe sudah ada
if not exist "cloudflared.exe" (
    echo [*] Mendownload Cloudflare Tunnel (hanya sekali di awal)...
    powershell -Command "[Net.ServicePointManager]::SecurityProtocol = [Net.SecurityProtocolType]::Tls12; (New-Object System.Net.WebClient).DownloadFile('https://github.com/cloudflare/cloudflared/releases/latest/download/cloudflared-windows-amd64.exe', 'cloudflared.exe')"
    if not exist "cloudflared.exe" (
        echo [!] Gagal mendownload otomatis.
        pause
        exit /b
    )
    echo [*] Cloudflare Tunnel berhasil didownload!
    echo.
)

:: 4. Jalankan Laravel Backend di background jika belum berjalan
echo [*] Memastikan server backend Laravel berjalan...
start "Laravel Server (GBI Shekinah Glory)" cmd /k "%PHP_BIN% artisan serve"
timeout /t 2 /nobreak >nul

:: 5. Jalankan Cloudflare Tunnel
echo.
echo ========================================================
echo   SEDANG MEMBUAT LINK PUBLIK INTERNET...
echo   Cari tulisan URL di bawah ini yang berakhiran:
echo   --^>  https://[nama-acak].trycloudflare.com  ^<--
echo.
echo   Link tersebut bisa langsung Anda copy & buka di HP!
echo   (Jangan tutup jendela ini selama website ingin online)
echo ========================================================
echo.

cloudflared.exe tunnel --url http://localhost:8000
pause
