@echo off
powershell -NoProfile -ExecutionPolicy Bypass -File "%%~dp0_safe_install.ps1"
exit /b %1%
