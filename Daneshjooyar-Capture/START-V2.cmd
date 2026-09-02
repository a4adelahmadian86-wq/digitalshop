@echo off
setlocal EnableExtensions

title Daneshjooyar Capture V2

set "SCRIPT=%USERPROFILE%\Desktop\Daneshjooyar-Capture\Daneshjooyar-Capture-V2.ps1"

echo.
echo ============================================================
echo              DANESHJOOYAR CAPTURE V2
echo ============================================================
echo.

if not exist "%SCRIPT%" (
echo ERROR:
echo Script not found:
echo %SCRIPT%
echo.
pause
exit /b 1
)

echo Starting...
echo.
echo A small floating button will appear on the page:
echo.
echo              [ کپی این صفحه ]
echo.
echo Click it and WAIT until:
echo.
echo              [ ✓ کپی کامل شد ]
echo.
echo Do NOT change the page during capture.
echo.
echo ============================================================
echo.

powershell.exe -NoLogo -NoProfile -ExecutionPolicy Bypass -File "%SCRIPT%"

echo.
echo ============================================================
echo Capture stopped.
echo ============================================================
echo.

pause
