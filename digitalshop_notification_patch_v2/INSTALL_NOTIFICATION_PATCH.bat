@echo off
setlocal
set "PROJECT=C:\xampp\htdocs\digitalshop"
set "PATCH=%~dp0"

echo Installing DigitalShop notification files...

if not exist "%PROJECT%" (
  echo ERROR: Project path not found: %PROJECT%
  exit /b 1
)

xcopy "%PATCH%app" "%PROJECT%app" /E /I /Y >nul
xcopy "%PATCH%database" "%PROJECT%database" /E /I /Y >nul
xcopy "%PATCH%resources" "%PROJECT%resources" /E /I /Y >nul

copy /Y "%PATCH%NOTIFICATION_CSS.txt" "%PROJECT%NOTIFICATION_CSS.txt" >nul
copy /Y "%PATCH%README.txt" "%PROJECT%README_NOTIFICATION.txt" >nul

echo.
echo New notification files copied.
echo.
echo MANUAL MERGES STILL REQUIRED:
echo 1. routes/web.php       - see ROUTES_SNIPPET.txt
echo 2. app/Providers/AppServiceProvider.php - see APP_SERVICE_PROVIDER_SNIPPET.txt
echo 3. resources/views/partials/header.blade.php - add notification-center include from HEADER_SNIPPET.txt
echo 4. public/css/style.css - append NOTIFICATION_CSS.txt
echo 5. scheduler - see SCHEDULER_SNIPPET.txt
echo 6. role notification examples - see ROLE_NOTIFICATION_SNIPPET.txt
echo.
echo Then run:
echo   php artisan migrate
echo   php artisan optimize:clear
echo   php artisan digitalshop:recommendations --dry-run
echo   php artisan digitalshop:recommendations
echo.
pause
