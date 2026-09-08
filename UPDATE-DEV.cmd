@echo off
setlocal EnableExtensions EnableDelayedExpansion
title DigitalShop - DEV Updater

REM ============================================================
REM DIGITALSHOP DEV UPDATER
REM SOURCE OF TRUTH: GitHub branch "dev"
REM MAIN PROJECT / MAIN DATABASE ARE NEVER MODIFIED.
REM DEV DATABASE IS PRESERVED.
REM LOCAL .env AND storage ARE PRESERVED.
REM ============================================================

set "OWNER=a4adelahmadian86-wq"
set "REPO=digitalshop"
set "BRANCH=dev"
set "DEV=%USERPROFILE%\Desktop\digitalshop-ai-dev"
set "TOKEN_FILE=%USERPROFILE%\Desktop\github_token.txt"
set "PORT=8001"
set "MYSQL=C:\xampp\mysql\bin\mysql.exe"
set "GITHUB_API=https://api.github.com/repos/%OWNER%/%REPO%"
set "ZIP_URL=https://github.com/%OWNER%/%REPO%/archive/refs/heads/%BRANCH%.zip"
set "TEMP_ROOT=%TEMP%\digitalshop-dev-update"
set "ZIP=%TEMP_ROOT%\digitalshop-dev.zip"
set "EXTRACT=%TEMP_ROOT%\extract"
set "BACKUP=%TEMP_ROOT%\current-backup"
set "OLDDEV=%USERPROFILE%\Desktop\digitalshop-ai-dev.previous"

cls
echo ============================================================
echo        DIGITALSHOP - SAFE DEV UPDATER
 echo ============================================================
echo.
echo SOURCE BRANCH : %BRANCH%
echo DEV PROJECT   : %DEV%
echo DEV DATABASE  : digitalshop_dev
echo URL           : http://127.0.0.1:%PORT%
echo.
echo MAIN PROJECT  : NOT MODIFIED
echo MAIN DATABASE : NOT MODIFIED
echo DEV DATABASE  : PRESERVED
echo .env          : PRESERVED
echo storage       : PRESERVED
echo ============================================================
echo.

if not exist "%TOKEN_FILE%" (
    echo ERROR: GitHub token not found:
    echo %TOKEN_FILE%
    pause
    exit /b 1
)

if not exist "C:\xampp\php\php.exe" (
    echo ERROR: C:\xampp\php\php.exe not found.
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

REM Stop only the Laravel DEV server on port 8001.
for /f "tokens=5" %%P in ('netstat -ano ^| findstr ":%PORT%" ^| findstr "LISTENING"') do (
    echo Stopping process on port %PORT%: %%P
    taskkill /PID %%P /F >nul 2>&1
)

if exist "%TEMP_ROOT%" rmdir /s /q "%TEMP_ROOT%" >nul 2>&1
if exist "%OLDDEV%" rmdir /s /q "%OLDDEV%" >nul 2>&1
mkdir "%EXTRACT%" >nul 2>&1
mkdir "%BACKUP%" >nul 2>&1

set "PS1=%TEMP_ROOT%\download.ps1"
>"%PS1%" echo $ErrorActionPreference='Stop'
>>"%PS1%" echo $h=@{Authorization='Bearer %GITHUB_TOKEN%';Accept='application/vnd.github+json';'X-GitHub-Api-Version'='2022-11-28';'User-Agent'='DigitalShop-DEV-Updater'}
>>"%PS1%" echo $b=Invoke-RestMethod -Uri '%GITHUB_API%/branches/%BRANCH%' -Headers $h
>>"%PS1%" echo Write-Host ('DEV commit: '+$b.commit.sha)
>>"%PS1%" echo Invoke-WebRequest -Uri '%ZIP_URL%' -Headers $h -OutFile '%ZIP%'
>>"%PS1%" echo Expand-Archive -LiteralPath '%ZIP%' -DestinationPath '%EXTRACT%' -Force
>>"%PS1%" echo $d=Get-ChildItem -LiteralPath '%EXTRACT%' -Directory ^| Select-Object -First 1
>>"%PS1%" echo if(-not $d){throw 'GitHub archive extraction failed.'}
>>"%PS1%" echo if(-not (Test-Path (Join-Path $d.FullName 'artisan'))){throw 'Downloaded branch is not a Laravel project.'}
>>"%PS1%" echo $d.FullName ^| Set-Content -LiteralPath '%TEMP_ROOT%\source.txt' -Encoding UTF8

powershell.exe -NoProfile -ExecutionPolicy Bypass -File "%PS1%"
if errorlevel 1 (
    echo.
    echo ERROR: GitHub DEV download failed.
    pause
    exit /b 1
)

set /p SOURCE=<"%TEMP_ROOT%\source.txt"

REM Preserve local environment and runtime/uploaded files.
if exist "%DEV%\.env" copy /y "%DEV%\.env" "%BACKUP%\.env" >nul
if exist "%DEV%\storage" xcopy "%DEV%\storage" "%BACKUP%\storage\" /E /I /H /Y >nul
if exist "%DEV%\.main_database_imported" copy /y "%DEV%\.main_database_imported" "%BACKUP%\.main_database_imported" >nul

REM Replace source code. The old DEV folder is kept as a rollback copy.
if exist "%DEV%" (
    ren "%DEV%" "digitalshop-ai-dev.previous" >nul 2>&1
    if errorlevel 1 (
        echo.
        echo ERROR: Could not move the existing DEV folder.
        echo Close any program using the DEV project and run again.
        pause
        exit /b 1
    )
)

move "%SOURCE%" "%DEV%" >nul
if errorlevel 1 (
    echo ERROR: Could not install downloaded DEV source.
    echo Your previous DEV copy remains at:
    echo %OLDDEV%
    pause
    exit /b 1
)

if exist "%BACKUP%\.env" copy /y "%BACKUP%\.env" "%DEV%\.env" >nul
if exist "%BACKUP%\storage" xcopy "%BACKUP%\storage" "%DEV%\storage\" /E /I /H /Y >nul

cd /d "%DEV%"
set "PATH=C:\xampp\php;%PATH%"

if exist "%DEV%\composer.json" (
    echo.
    echo Installing PHP dependencies...
    call composer install --no-interaction --prefer-dist
    if errorlevel 1 goto UPDATE_FAILED
)

if exist "%DEV%\package.json" (
    echo.
    echo Installing frontend dependencies...
    if exist "%DEV%\package-lock.json" (
        call npm ci
        if errorlevel 1 call npm install
    ) else (
        call npm install
    )
)

echo.
echo Clearing Laravel caches...
php artisan optimize:clear
if errorlevel 1 goto UPDATE_FAILED

if exist "%MYSQL%" (
    echo.
    echo Checking DEV database only...
    "%MYSQL%" -u root -e "CREATE DATABASE IF NOT EXISTS digitalshop_dev CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
    if errorlevel 1 goto UPDATE_FAILED
)

echo.
echo Running DEV migrations only...
php artisan migrate --force
if errorlevel 1 goto UPDATE_FAILED

if exist "%DEV%\public\storage" rmdir /s /q "%DEV%\public\storage" >nul 2>&1
php artisan storage:link >nul 2>&1

REM Critical route checks: these must exist before the new DEV is accepted.
echo.
echo Verifying critical admin routes...
php artisan route:list --name=admin.integrations.index >nul 2>&1
if errorlevel 1 (
    echo ERROR: admin.integrations.index is missing from DEV.
    goto UPDATE_FAILED
)
php artisan route:list --name=admin.dashboard >nul 2>&1
if errorlevel 1 (
    echo ERROR: admin.dashboard is missing from DEV.
    goto UPDATE_FAILED
)

if exist "%TEMP_ROOT%" rmdir /s /q "%TEMP_ROOT%" >nul 2>&1

echo.
echo ============================================================
echo              DEV UPDATE COMPLETE
 echo ============================================================
echo.
echo BRANCH  : %BRANCH%
echo PROJECT : %DEV%
echo DATABASE: digitalshop_dev
echo URL     : http://127.0.0.1:%PORT%
echo.
echo MAIN PROJECT  : NOT MODIFIED
echo MAIN DATABASE : NOT MODIFIED
echo.
echo Previous DEV copy is kept at:
echo %OLDDEV%
echo.
echo Starting Laravel DEV server...
echo Press Ctrl+C to stop it.
echo ============================================================
echo.

php artisan serve --host=127.0.0.1 --port=%PORT%
endlocal
exit /b 0

:UPDATE_FAILED
echo.
echo ============================================================
echo DEV UPDATE FAILED - ROLLBACK COPY WAS KEPT
 echo ============================================================
echo.
echo Current attempted DEV: %DEV%
echo Previous DEV copy     : %OLDDEV%
echo.
echo MAIN PROJECT  : NOT MODIFIED
echo MAIN DATABASE : NOT MODIFIED
echo DEV DATABASE  : NOT RESET
echo.
echo The previous DEV source was intentionally NOT deleted.
echo Fix the reported issue before trying again.
echo ============================================================
pause
endlocal
exit /b 1
