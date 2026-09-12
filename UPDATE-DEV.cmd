@echo off
setlocal EnableExtensions EnableDelayedExpansion
title DigitalShop - LIGHT DEV UPDATER

REM ============================================================
REM DIGITALSHOP - LIGHT DEV UPDATER
REM SOURCE OF TRUTH : GitHub branch "dev"
REM MAIN PROJECT     : NEVER MODIFIED
REM MAIN DATABASE    : NEVER MODIFIED
REM DEV .env/storage : PRESERVED
REM
REM First run: downloads the complete DEV archive.
REM Later runs: downloads ONLY files changed since the last DEV SHA.
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
set "RAW_BASE=https://raw.githubusercontent.com/%OWNER%/%REPO%"
set "TEMP_ROOT=%TEMP%\digitalshop-dev-light-update"
set "ZIP=%TEMP_ROOT%\digitalshop-dev.zip"
set "EXTRACT=%TEMP_ROOT%\extract"
set "SOURCE="

cls
echo ============================================================
echo          DIGITALSHOP - LIGHT DEV UPDATER
echo ============================================================
echo.
echo SOURCE BRANCH : %BRANCH%
echo DEV PROJECT   : %DEV%
echo DEV DATABASE  : digitalshop_dev
echo URL           : http://127.0.0.1:%PORT%
echo.
echo MAIN PROJECT  : NEVER MODIFIED
echo MAIN DATABASE : NEVER MODIFIED
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

if exist "%TEMP_ROOT%" rmdir /s /q "%TEMP_ROOT%" >nul 2>&1
mkdir "%TEMP_ROOT%" >nul 2>&1

set "PS1=%TEMP_ROOT%\update.ps1"
>"%PS1%" echo $ErrorActionPreference='Stop'
>>"%PS1%" echo $h=@{Authorization='Bearer %GITHUB_TOKEN%';Accept='application/vnd.github+json';'X-GitHub-Api-Version'='2022-11-28';'User-Agent'='DigitalShop-DEV-Light-Updater'}
>>"%PS1%" echo $api='%GITHUB_API%'
>>"%PS1%" echo $raw='%RAW_BASE%'
>>"%PS1%" echo $branch='%BRANCH%'
>>"%PS1%" echo $dev='%DEV%'
>>"%PS1%" echo $tmp='%TEMP_ROOT%'
>>"%PS1%" echo $state=Join-Path $dev '.dev-version'
>>"%PS1%" echo $remote=(Invoke-RestMethod -Uri ($api+'/branches/'+$branch) -Headers $h).commit.sha
>>"%PS1%" echo Write-Host ('REMOTE DEV SHA: '+$remote)
>>"%PS1%" echo $local=''
>>"%PS1%" echo if(Test-Path $state){$local=(Get-Content -LiteralPath $state -Raw).Trim()}
>>"%PS1%" echo if($local -eq $remote -and (Test-Path (Join-Path $dev 'artisan'))){
>>"%PS1%" echo   Write-Host 'DEV is already up to date. No source download required.'
>>"%PS1%" echo   $remote ^| Set-Content -LiteralPath (Join-Path $tmp 'remote.txt') -Encoding ASCII
>>"%PS1%" echo   'NO_CHANGE' ^| Set-Content -LiteralPath (Join-Path $tmp 'mode.txt') -Encoding ASCII
>>"%PS1%" echo   exit 0
>>"%PS1%" echo }
>>"%PS1%" echo if([string]::IsNullOrWhiteSpace($local) -or -not (Test-Path (Join-Path $dev 'artisan'))){
>>"%PS1%" echo   Write-Host 'First/full synchronization: downloading the DEV archive once...'
>>"%PS1%" echo   $zip=Join-Path $tmp 'digitalshop-dev.zip'
>>"%PS1%" echo   Invoke-WebRequest -Uri ($api+'/zipball/'+$remote) -Headers $h -OutFile $zip
>>"%PS1%" echo   $ex=Join-Path $tmp 'extract'
>>"%PS1%" echo   Expand-Archive -LiteralPath $zip -DestinationPath $ex -Force
>>"%PS1%" echo   $d=Get-ChildItem -LiteralPath $ex -Directory ^| Select-Object -First 1
>>"%PS1%" echo   if(-not $d -or -not (Test-Path (Join-Path $d.FullName 'artisan'))){throw 'GitHub archive extraction failed.'}
>>"%PS1%" echo   $d.FullName ^| Set-Content -LiteralPath (Join-Path $tmp 'source.txt') -Encoding ASCII
>>"%PS1%" echo   $remote ^| Set-Content -LiteralPath (Join-Path $tmp 'remote.txt') -Encoding ASCII
>>"%PS1%" echo   'FULL' ^| Set-Content -LiteralPath (Join-Path $tmp 'mode.txt') -Encoding ASCII
>>"%PS1%" echo   exit 0
>>"%PS1%" echo }
>>"%PS1%" echo Write-Host ('Incremental synchronization: '+$local+' -> '+$remote)
>>"%PS1%" echo $cmp=Invoke-RestMethod -Uri ($api+'/compare/'+$local+'...'+$remote) -Headers $h
>>"%PS1%" echo $files=@($cmp.files)
>>"%PS1%" echo Write-Host ('Changed files: '+$files.Count)
>>"%PS1%" echo foreach($f in $files){
>>"%PS1%" echo   $p=$f.filename
>>"%PS1%" echo   if($p -eq '.env' -or $p.StartsWith('storage/') -or $p.StartsWith('public/storage/')){continue}
>>"%PS1%" echo   if($f.status -eq 'removed'){
>>"%PS1%" echo     $target=Join-Path $dev ($p -replace '/','\\')
>>"%PS1%" echo     if(Test-Path -LiteralPath $target -PathType Leaf){Remove-Item -LiteralPath $target -Force}
>>"%PS1%" echo     continue
>>"%PS1%" echo   }
>>"%PS1%" echo   $target=Join-Path $dev ($p -replace '/','\\')
>>"%PS1%" echo   $dir=Split-Path -Parent $target
>>"%PS1%" echo   if($dir -and -not (Test-Path $dir)){New-Item -ItemType Directory -Path $dir -Force ^| Out-Null}
>>"%PS1%" echo   $url=$raw+'/'+$remote+'/'+($p -replace ' ','%%20')
>>"%PS1%" echo   Invoke-WebRequest -Uri $url -OutFile $target
>>"%PS1%" echo }
>>"%PS1%" echo $remote ^| Set-Content -LiteralPath (Join-Path $tmp 'remote.txt') -Encoding ASCII
>>"%PS1%" echo 'INCREMENTAL' ^| Set-Content -LiteralPath (Join-Path $tmp 'mode.txt') -Encoding ASCII

powershell.exe -NoProfile -ExecutionPolicy Bypass -File "%PS1%"
if errorlevel 1 (
    echo.
    echo ERROR: GitHub DEV synchronization failed.
    goto FAIL
)

set /p REMOTE=<"%TEMP_ROOT%\remote.txt"
set /p MODE=<"%TEMP_ROOT%\mode.txt"

if /I "%MODE%"=="NO_CHANGE" goto AFTER_SYNC

if /I "%MODE%"=="FULL" (
    set /p SOURCE=<"%TEMP_ROOT%\source.txt"
    if not defined SOURCE (
        echo ERROR: Full archive source path is empty.
        goto FAIL
    )
    echo.
    echo Synchronizing first/full source into DEV...
    echo .env and storage remain excluded.
    robocopy "%SOURCE%" "%DEV%" /MIR /R:2 /W:1 /COPY:DAT /DCOPY:DAT /XJ /XD "%SOURCE%\storage" "%SOURCE%\public\storage" "%DEV%\storage" "%DEV%\public\storage" /XF "%SOURCE%\.env" "%DEV%\.env"
    set "ROBO=!ERRORLEVEL!"
    if !ROBO! GEQ 8 (
        echo ERROR: Full synchronization failed. Robocopy code: !ROBO!
        goto FAIL
    )
)

:AFTER_SYNC
if not exist "%DEV%\artisan" (
    echo ERROR: DEV Laravel project is missing artisan.
    goto FAIL
)

if /I "%DEV%"=="C:\xampp\htdocs\digitalshop" (
    echo FATAL SAFETY ERROR: DEV path equals MAIN project path.
    goto FAIL
)

if not exist "%DEV%\.env" (
    echo ERROR: DEV .env does not exist. It was not created or overwritten.
    goto FAIL
)

cd /d "%DEV%"
set "PATH=C:\xampp\php;%PATH%"

echo.
echo Checking PHP dependencies without unnecessary reinstall...
call composer install --no-interaction --prefer-dist --no-progress
if errorlevel 1 goto FAIL

if exist "%DEV%\package.json" (
    if exist "%DEV%\package-lock.json" (
        if not exist "%DEV%\node_modules" (
            echo Installing frontend dependencies for the first time...
            call npm ci --no-audit --no-fund
            if errorlevel 1 goto FAIL
        ) else (
            echo Existing node_modules found; skipping npm reinstall.
        )
    )
)

echo.
echo Clearing Laravel caches...
php artisan optimize:clear
if errorlevel 1 goto FAIL

if exist "%MYSQL%" (
    "%MYSQL%" -u root -e "CREATE DATABASE IF NOT EXISTS digitalshop_dev CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
    if errorlevel 1 goto FAIL
)

echo.
echo Running DEV migrations only...
php artisan migrate --force
if errorlevel 1 goto FAIL

echo.
echo Refreshing local DEV version marker...
echo %REMOTE%>"%DEV%\.dev-version"

echo.
echo ============================================================
echo             LIGHT DEV UPDATE COMPLETE
echo ============================================================
echo.
echo DEV SHA       : %REMOTE%
echo SYNC MODE     : %MODE%
echo DEV PROJECT   : %DEV%
echo DEV DATABASE  : digitalshop_dev
echo.
echo MAIN PROJECT  : NEVER MODIFIED
echo MAIN DATABASE : NEVER MODIFIED
echo .env          : PRESERVED
echo storage       : PRESERVED
echo.
echo Future runs download ONLY changed source files.
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
echo               DEV UPDATE FAILED
necho ============================================================
echo.
echo MAIN PROJECT  : NEVER MODIFIED
echo MAIN DATABASE : NEVER MODIFIED
echo DEV DATABASE  : NOT RESET
echo .env           : PRESERVED
necho storage        : PRESERVED
if exist "%TEMP_ROOT%" rmdir /s /q "%TEMP_ROOT%" >nul 2>&1
pause
endlocal
exit /b 1