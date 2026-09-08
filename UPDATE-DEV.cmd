@echo off
setlocal EnableExtensions EnableDelayedExpansion
title DigitalShop - FINAL SAFE DEV UPDATER

REM ============================================================
REM DIGITALSHOP - FINAL SAFE DEV UPDATER
REM SOURCE OF TRUTH : GitHub branch "dev"
REM MAIN PROJECT     : NEVER MODIFIED
REM MAIN DATABASE    : NEVER MODIFIED
REM DEV DATABASE     : digitalshop_dev ONLY
REM LOCAL .env       : PRESERVED
REM LOCAL storage    : PRESERVED
REM ============================================================

set "OWNER=a4adelahmadian86-wq"
set "REPO=digitalshop"
set "BRANCH=dev"
set "DEV=%USERPROFILE%\Desktop\digitalshop-ai-dev"
set "TOKEN_FILE=%USERPROFILE%\Desktop\github_token.txt"
set "PORT=8001"
set "MYSQL=C:\xampp\mysql\bin\mysql.exe"
set "PHP=C:\xampp\php\php.exe"
set "GITHUB_API=https://api.github.com/repos/%OWNER%/%REPO%"
set "ZIP_URL=https://github.com/%OWNER%/%REPO%/archive/refs/heads/%BRANCH%.zip"
set "TEMP_ROOT=%TEMP%\digitalshop-dev-final-update"
set "ZIP=%TEMP_ROOT%\digitalshop-dev.zip"
set "EXTRACT=%TEMP_ROOT%\extract"
set "SOURCE="

cls
echo ============================================================
echo        DIGITALSHOP - FINAL SAFE DEV UPDATER
echo ============================================================
echo.
echo SOURCE BRANCH : %BRANCH%
echo DEV PROJECT   : %DEV%
echo DEV DATABASE  : digitalshop_dev
echo URL           : http://127.0.0.1:%PORT%
echo.
echo MAIN PROJECT  : NEVER MODIFIED
echo MAIN DATABASE : NEVER MODIFIED
echo DEV DATABASE  : PRESERVED
echo .env          : PRESERVED
echo storage       : PRESERVED
echo ============================================================
echo.

REM ---------- Required local files ----------
if not exist "%TOKEN_FILE%" (
    echo ERROR: GitHub token not found:
    echo %TOKEN_FILE%
    echo.
    echo Create github_token.txt on the Desktop and put the GitHub token on its first line.
    pause
    exit /b 1
)

if not exist "%PHP%" (
    echo ERROR: PHP not found:
    echo %PHP%
    pause
    exit /b 1
)

set "GITHUB_TOKEN="
for /f "usebackq delims=" %%T in ("%TOKEN_FILE%") do if not defined GITHUB_TOKEN set "GITHUB_TOKEN=%%T"
if not defined GITHUB_TOKEN (
    echo ERROR: GitHub token file is empty.
    pause
    exit /b 1
)

REM ---------- Stop only the DEV Laravel server ----------
echo Stopping Laravel DEV server on port %PORT%...
for /f "tokens=5" %%P in ('netstat -ano ^| findstr ":%PORT%" ^| findstr "LISTENING"') do (
    echo Stopping PID %%P
    taskkill /PID %%P /F >nul 2>&1
)
timeout /t 2 /nobreak >nul

REM ---------- Clean temporary workspace only ----------
if exist "%TEMP_ROOT%" rmdir /s /q "%TEMP_ROOT%" >nul 2>&1
mkdir "%EXTRACT%" >nul 2>&1

REM ---------- Download exact current DEV branch ----------
echo.
echo Downloading GitHub DEV...
set "PS1=%TEMP_ROOT%\download.ps1"
>"%PS1%" echo $ErrorActionPreference='Stop'
>>"%PS1%" echo $h=@{Authorization='Bearer %GITHUB_TOKEN%';Accept='application/vnd.github+json';'X-GitHub-Api-Version'='2022-11-28';'User-Agent'='DigitalShop-DEV-Updater'}
>>"%PS1%" echo $b=Invoke-RestMethod -Uri '%GITHUB_API%/branches/%BRANCH%' -Headers $h
>>"%PS1%" echo Write-Host ('DEV commit: '+$b.commit.sha)
>>"%PS1%" echo Invoke-WebRequest -Uri '%ZIP_URL%' -Headers $h -OutFile '%ZIP%'
>>"%PS1%" echo Expand-Archive -LiteralPath '%ZIP%' -DestinationPath '%EXTRACT%' -Force

powershell.exe -NoProfile -ExecutionPolicy Bypass -File "%PS1%"
if errorlevel 1 (
    echo.
    echo ERROR: GitHub DEV download failed.
    goto FAIL
)

REM ---------- Find extracted Laravel root using CMD only ----------
REM IMPORTANT: Do NOT read the path through a UTF-8 text file.
REM Windows PowerShell UTF-8 output may add a BOM and corrupt CMD paths.
set "SOURCE="
for /d %%D in ("%EXTRACT%\*") do (
    if exist "%%~fD\artisan" set "SOURCE=%%~fD"
)

if not defined SOURCE (
    echo.
    echo ERROR: GitHub archive was extracted, but the Laravel project folder was not found.
    goto FAIL
)

echo.
echo GitHub source verified:
echo %SOURCE%

REM ============================================================
REM IMPORTANT:
REM We DO NOT rename/delete the DEV project directory.
REM This avoids file-lock failures from PhpStorm, VS Code, Explorer,
REM antivirus, and other processes.
REM
REM We synchronize GitHub DEV IN PLACE and explicitly exclude:
REM   .env          - local configuration
REM   storage       - uploaded/runtime files
REM   public\storage - local storage link/runtime path
REM ============================================================

if not exist "%DEV%\artisan" (
    echo.
    echo DEV project does not exist yet. Creating it...
    mkdir "%DEV%" >nul 2>&1
    if errorlevel 1 (
        echo ERROR: Cannot create DEV project directory.
        goto FAIL
    )
)

REM ---------- Safety check: never point at the MAIN project ----------
if /I "%DEV%"=="C:\xampp\htdocs\digitalshop" (
    echo.
    echo FATAL SAFETY ERROR: DEV path equals MAIN project path.
    echo Update cancelled.
    goto FAIL
)

REM ---------- Sync source in place ----------
echo.
echo Synchronizing source code into DEV...
echo Local .env and storage are excluded and will NOT be overwritten.

robocopy "%SOURCE%" "%DEV%" /MIR /R:3 /W:2 /COPY:DAT /DCOPY:DAT /XJ /XD "%SOURCE%\storage" "%SOURCE%\public\storage" "%DEV%\storage" "%DEV%\public\storage" /XF "%SOURCE%\.env" "%DEV%\.env"
set "ROBO=%ERRORLEVEL%"
if %ROBO% GEQ 8 (
    echo.
    echo ERROR: Source synchronization failed. Robocopy code: %ROBO%
    goto FAIL
)

REM Robocopy /MIR must never be allowed to remove local runtime directories.
if not exist "%DEV%\storage" mkdir "%DEV%\storage" >nul 2>&1
if not exist "%DEV%\storage\app" mkdir "%DEV%\storage\app" >nul 2>&1
if not exist "%DEV%\storage\framework" mkdir "%DEV%\storage\framework" >nul 2>&1
if not exist "%DEV%\storage\logs" mkdir "%DEV%\storage\logs" >nul 2>&1

REM ---------- Preserve local .env ----------
if not exist "%DEV%\.env" (
    echo.
    echo WARNING: DEV .env does not exist.
    echo Create it before continuing.
    goto FAIL
)

cd /d "%DEV%"
set "PATH=C:\xampp\php;%PATH%"

REM ---------- Dependencies ----------
echo.
echo Installing/checking PHP dependencies...
call composer install --no-interaction --prefer-dist
if errorlevel 1 goto FAIL

if exist "%DEV%\package.json" (
    echo.
    echo Installing/checking frontend dependencies...
    if exist "%DEV%\package-lock.json" (
        call npm ci
        if errorlevel 1 call npm install
    ) else (
        call npm install
    )
)

REM ---------- Laravel health ----------
echo.
echo Clearing Laravel caches...
php artisan optimize:clear
if errorlevel 1 goto FAIL

REM ---------- DEV database only ----------
if exist "%MYSQL%" (
    echo.
    echo Ensuring DEV database exists...
    "%MYSQL%" -u root -e "CREATE DATABASE IF NOT EXISTS digitalshop_dev CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
    if errorlevel 1 goto FAIL
)

echo.
echo Running DEV migrations only...
php artisan migrate --force
if errorlevel 1 goto FAIL

REM ---------- Storage link ----------
echo.
echo Checking Laravel storage link...
if exist "%DEV%\public\storage" (
    rmdir "%DEV%\public\storage" >nul 2>&1
)
php artisan storage:link
if errorlevel 1 (
    echo WARNING: storage:link could not be recreated.
    echo Continuing because image delivery also supports the controller route.
)

REM ---------- Critical route verification ----------
echo.
echo Verifying critical routes...
php artisan route:list --name=admin.integrations.index >nul 2>&1
if errorlevel 1 (
    echo ERROR: admin.integrations.index is missing.
    goto FAIL
)

php artisan route:list --name=admin.dashboard >nul 2>&1
if errorlevel 1 (
    echo ERROR: admin.dashboard is missing.
    goto FAIL
)

php artisan route:list --name=product.image >nul 2>&1
if errorlevel 1 (
    echo WARNING: product.image route was not found.
    echo The updater will continue, but image routing must be checked.
)

REM ---------- Complete ----------
echo.
echo ============================================================
echo              DEV UPDATE COMPLETE
echo ============================================================
echo.
echo SOURCE BRANCH : %BRANCH%
echo DEV PROJECT   : %DEV%
echo DEV DATABASE  : digitalshop_dev
echo URL           : http://127.0.0.1:%PORT%
echo.
echo MAIN PROJECT  : NEVER MODIFIED
echo MAIN DATABASE : NEVER MODIFIED
echo.
echo GitHub DEV source was synchronized IN PLACE.
echo Local .env and storage were preserved.
echo ============================================================
echo.

if exist "%TEMP_ROOT%" rmdir /s /q "%TEMP_ROOT%" >nul 2>&1

echo Starting Laravel DEV server...
echo Press Ctrl+C to stop it.
echo.
php artisan serve --host=127.0.0.1 --port=%PORT%
endlocal
exit /b 0

:FAIL
echo.
echo ============================================================
echo              DEV UPDATE FAILED
echo ============================================================
echo.
echo MAIN PROJECT  : NEVER MODIFIED
echo MAIN DATABASE : NOT RESET
echo DEV DATABASE  : NOT RESET
echo.
echo No automatic deletion of the DEV project was performed.
echo The error shown above must be fixed before continuing.
echo.
if exist "%TEMP_ROOT%" rmdir /s /q "%TEMP_ROOT%" >nul 2>&1
pause
endlocal
exit /b 1
